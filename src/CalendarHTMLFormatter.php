<?php

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Multiple\ExtraSmallEventHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\MediumEventHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\LargeEventHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Multiple\SmallEventHTMLFormatter;
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
     * @var array<string,array<string,OfferFormatter|EventFormatter>>
     */
    private $mapping;

    public function __construct(
        string $langCode = 'nl_BE',
        bool $hidePastDates = false,
        string $timeZone = 'Europe/Brussels'
    ) {
        date_default_timezone_set($timeZone);

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
                    'lg' => new LargeEventHTMLFormatter($langCode, $hidePastDates),
                    'md' => new MediumEventHTMLFormatter($langCode, $hidePastDates),
                    'sm' => new SmallEventHTMLFormatter($langCode),
                    'xs' => new ExtraSmallEventHTMLFormatter($langCode)
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
            throw new FormatterException($format . ' format not supported for ' . ($calenderType ?: 'null'));
        }

        return $formatter->format($offer);
    }
}
