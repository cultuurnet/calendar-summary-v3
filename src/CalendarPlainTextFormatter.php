<?php

namespace CultuurNet\CalendarSummaryV3;

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

class CalendarPlainTextFormatter implements CalendarFormatterInterface
{
    protected $mapping = array();

    public function __construct($langCode = 'nl_NL', $hidePastDates = false)
    {
        $this->mapping = [
            Offer::CALENDAR_TYPE_SINGLE =>
                [
                    'lg' => new LargeSinglePlainTextFormatter($langCode),
                    'md' => new MediumSinglePlainTextFormatter($langCode),
                    'sm' => new SmallSinglePlainTextFormatter($langCode),
                    'xs' => new SmallSinglePlainTextFormatter($langCode)
                ],
            Offer::CALENDAR_TYPE_MULTIPLE =>
                [
                    'lg' => new LargeMultiplePlainTextFormatter($langCode, $hidePastDates),
                    'md' => new MediumMultiplePlainTextFormatter($langCode, $hidePastDates),
                    'sm' => new SmallMultiplePlainTextFormatter($langCode, $hidePastDates)
                ],
            Offer::CALENDAR_TYPE_PERIODIC =>
                [
                    'lg' => new LargePeriodicPlainTextFormatter($langCode),
                    'md' => new MediumPeriodicPlainTextFormatter($langCode),
                    'sm' => new SmallPeriodicPlainTextFormatter($langCode),
                    'xs' => new ExtraSmallPeriodicPlainTextFormatter($langCode),
                ],
            Offer::CALENDAR_TYPE_PERMANENT =>
                [
                    'lg' => new LargePermanentPlainTextFormatter($langCode),
                    'md' => new MediumPermanentPlainTextFormatter($langCode)
                ],
        ];
    }

    public function format(Offer $offer, $format)
    {
        $calenderType = $offer->getCalendarType();

        if (isset($this->mapping[$calenderType][$format])) {
            $formatter = $this->mapping[$calenderType][$format];
        } else {
            throw new FormatterException($format . ' format not supported for ' . $calenderType);
        }

        return $formatter->format($offer);
    }
}
