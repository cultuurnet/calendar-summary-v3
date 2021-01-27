<?php

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Middleware\FormatterMiddleware;
use CultuurNet\CalendarSummaryV3\Middleware\NonAvailablePlaceHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\ExtraSmallMultipleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\MediumMultipleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\LargeMultipleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\SmallMultipleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Permanent\MediumPermanentHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Single\LargeSingleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Single\MediumSingleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Single\SmallSingleHTMLFormatter;
use CultuurNet\SearchV3\ValueObjects\Offer;
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

    public function __construct($langCode = 'nl_BE', $hidePastDates = false, $timeZone = 'Europe/Brussels')
    {
        date_default_timezone_set($timeZone);

        $translator = new Translator($langCode);

        $this->mapping = [
            Offer::CALENDAR_TYPE_SINGLE =>
                [
                    'lg' => new LargeSingleHTMLFormatter($langCode),
                    'md' => new MediumSingleHTMLFormatter($langCode),
                    'sm' => new SmallSingleHTMLFormatter($langCode),
                    'xs' => new SmallSingleHTMLFormatter($langCode)
                ],
            Offer::CALENDAR_TYPE_MULTIPLE =>
                [
                    'lg' => new LargeMultipleHTMLFormatter($langCode, $hidePastDates),
                    'md' => new MediumMultipleHTMLFormatter($langCode, $hidePastDates),
                    'sm' => new SmallMultipleHTMLFormatter($langCode),
                    'xs' => new ExtraSmallMultipleHTMLFormatter($langCode)
                ],
            Offer::CALENDAR_TYPE_PERIODIC =>
                [
                    'lg' => new LargePeriodicHTMLFormatter($langCode),
                    'md' => new MediumPeriodicHTMLFormatter($langCode),
                    'sm' => new SmallPeriodicHTMLFormatter($langCode),
                    'xs' => new ExtraSmallPeriodicHTMLFormatter($langCode),
                ],
            Offer::CALENDAR_TYPE_PERMANENT =>
                [
                    'lg' => new LargePermanentHTMLFormatter($langCode),
                    'md' => new MediumPermanentHTMLFormatter($langCode),
                    'sm' => new MediumPermanentHTMLFormatter($langCode),
                    'xs' => new MediumPermanentHTMLFormatter($langCode)
                ],
        ];

        $this->middleware = new NonAvailablePlaceHTMLFormatter($translator);
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
