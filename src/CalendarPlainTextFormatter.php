<?php

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Single\LargeSinglePlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Single\MediumSinglePlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Single\SmallSinglePlainTextFormatter;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\CalendarSummaryV3\Periodic\ExtraSmallPeriodicPlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Permanent\LargePermanentPlainTextFormatter;

class CalendarPlainTextFormatter implements CalendarFormatterInterface
{
    protected $mapping = array();

    public function __construct()
    {
        $this->mapping = [
            Offer::CALENDAR_TYPE_SINGLE =>
                [
                    'lg' => new LargeSinglePlainTextFormatter(),
                    'md' => new MediumSinglePlainTextFormatter(),
                    'sm' => new SmallSinglePlainTextFormatter(),
                    'xs' => new SmallSinglePlainTextFormatter()
                ],
            Offer::CALENDAR_TYPE_MULTIPLE =>
                [
                    //'lg' => new LargeTimestampsPlainTextFormatter(),
                    //'md' => new MediumTimestampsPlainTextFormatter(),
                    //'sm' => new SmallTimestampsPlainTextFormatter(),
                    //'xs' => new ExtraSmallTimestampsPlainTextFormatter(),
                ],
            Offer::CALENDAR_TYPE_PERIODIC =>
                [
                    //'lg' => new LargePeriodPlainTextFormatter(),
                    //'md' => new MediumPeriodPlainTextFormatter(),
                    //'sm' => new SmallPeriodPlainTextFormatter(),
                    //'xs' => new ExtraSmallPeriodicPlainTextFormatter(),
                ],
            Offer::CALENDAR_TYPE_PERMANENT =>
                [
                    'lg' => new LargePermanentPlainTextFormatter(),
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
