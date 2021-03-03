<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Middleware\FormatterMiddleware;
use CultuurNet\CalendarSummaryV3\Middleware\NonAvailablePlaceHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\ExtraSmallMultipleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\MediumMultipleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\LargeMultipleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\SmallMultipleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Permanent\MediumPermanentHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Single\LargeSingleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Single\MediumSingleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Single\SmallSingleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Permanent\LargePermanentHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\ExtraSmallPeriodicHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\LargePeriodicHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\SmallPeriodicHTMLFormatter;

final class CalendarHTMLFormatter implements CalendarFormatterInterface
{
    /**
     * @var array[]
     */
    private $mapping;

    /**
     * @var FormatterMiddleware
     */
    private $middleware;

    public function __construct(
        string $langCode = 'nl_BE',
        bool $hidePastDates = false,
        string $timeZone = 'Europe/Brussels'
    ) {
        date_default_timezone_set($timeZone);

        $translator = new Translator($langCode);

        $this->mapping = [
            CalendarType::single()->toString() =>
                [
                    'lg' => new LargeSingleHTMLFormatter($translator),
                    'md' => new MediumSingleHTMLFormatter($translator),
                    'sm' => new SmallSingleHTMLFormatter($translator),
                    'xs' => new SmallSingleHTMLFormatter($translator),
                ],
            CalendarType::multiple()->toString() =>
                [
                    'lg' => new LargeMultipleHTMLFormatter($translator, $hidePastDates),
                    'md' => new MediumMultipleHTMLFormatter($translator, $hidePastDates),
                    'sm' => new SmallMultipleHTMLFormatter($translator),
                    'xs' => new ExtraSmallMultipleHTMLFormatter($translator),
                ],
            CalendarType::periodic()->toString() =>
                [
                    'lg' => new LargePeriodicHTMLFormatter($translator),
                    'md' => new MediumPeriodicHTMLFormatter($translator),
                    'sm' => new SmallPeriodicHTMLFormatter($translator),
                    'xs' => new ExtraSmallPeriodicHTMLFormatter($translator),
                ],
            CalendarType::permanent()->toString() =>
                [
                    'lg' => new LargePermanentHTMLFormatter($translator),
                    'md' => new MediumPermanentHTMLFormatter($translator),
                    'sm' => new MediumPermanentHTMLFormatter($translator),
                    'xs' => new MediumPermanentHTMLFormatter($translator),
                ],
        ];

        $this->middleware = new NonAvailablePlaceHTMLFormatter($translator);
    }

    /**
     * @throws FormatterException
     */
    public function format(Offer $offer, string $format): string
    {
        $calenderType = $offer->getCalendarType()->toString();

        if (isset($this->mapping[$calenderType][$format])) {
            $formatter = $this->mapping[$calenderType][$format];
        } else {
            throw new FormatterException($format . ' format not supported for ' . $calenderType);
        }

        return $this->middleware->format(
            $offer,
            function ($offer) use ($formatter) {
                return $formatter->format($offer);
            }
        );
    }
}
