<?php

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Middleware\FormatterMiddleware;
use CultuurNet\CalendarSummaryV3\Middleware\NonAvailablePlacePlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\ExtraSmallMultiplePlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\SmallMultiplePlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Permanent\MediumPermanentPlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Single\LargeSinglePlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Single\MediumSinglePlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Single\SmallSinglePlainTextFormatter;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\CalendarSummaryV3\Periodic\ExtraSmallPeriodicPlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\LargePeriodicPlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicPlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\SmallPeriodicPlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Permanent\LargePermanentPlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\LargeMultiplePlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\MediumMultiplePlainTextFormatter;

final class CalendarPlainTextFormatter implements CalendarFormatterInterface
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
            Offer::CALENDAR_TYPE_SINGLE =>
                [
                    'lg' => new LargeSinglePlainTextFormatter($translator),
                    'md' => new MediumSinglePlainTextFormatter($translator),
                    'sm' => new SmallSinglePlainTextFormatter($translator),
                    'xs' => new SmallSinglePlainTextFormatter($translator)
                ],
            Offer::CALENDAR_TYPE_MULTIPLE =>
                [
                    'lg' => new LargeMultiplePlainTextFormatter($translator, $hidePastDates),
                    'md' => new MediumMultiplePlainTextFormatter($translator, $hidePastDates),
                    'sm' => new SmallMultiplePlainTextFormatter($translator),
                    'xs' => new ExtraSmallMultiplePlainTextFormatter($langCode)
                ],
            Offer::CALENDAR_TYPE_PERIODIC =>
                [
                    'lg' => new LargePeriodicPlainTextFormatter($langCode),
                    'md' => new MediumPeriodicPlainTextFormatter($translator),
                    'sm' => new SmallPeriodicPlainTextFormatter($langCode),
                    'xs' => new ExtraSmallPeriodicPlainTextFormatter($langCode),
                ],
            Offer::CALENDAR_TYPE_PERMANENT =>
                [
                    'lg' => new LargePermanentPlainTextFormatter($langCode),
                    'md' => new MediumPermanentPlainTextFormatter($langCode),
                    'sm' => new MediumPermanentPlainTextFormatter($langCode),
                    'xs' => new MediumPermanentPlainTextFormatter($langCode)
                ],
        ];

        $this->middleware = new NonAvailablePlacePlainTextFormatter($translator);
    }

    /**
     * @throws FormatterException
     */
    public function format(Offer $offer, string $format): string
    {
        $calenderType = $offer->getCalendarType();

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
