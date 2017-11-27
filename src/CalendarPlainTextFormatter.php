<?php

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\SearchV3\ValueObjects\Offer;

use CultuurNet\CalendarSummaryV3\Periodic\ExtraSmallPeriodicPlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\LargePeriodicPlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicPlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\SmallPeriodicPlainTextFormatter;
use CultuurNet\CalendarSummary\Permanent\LargePermanentPlainTextFormatter;
use CultuurNet\CalendarSummary\Timestamps\ExtraSmallTimestampsPlainTextFormatter;
use CultuurNet\CalendarSummary\Timestamps\LargeTimestampsPlainTextFormatter;
use CultuurNet\CalendarSummary\Timestamps\MediumTimestampsPlainTextFormatter;
use CultuurNet\CalendarSummary\Timestamps\SmallTimestampsPlainTextFormatter;

class CalendarPlainTextFormatter implements CalendarFormatterInterface
{
    protected $mapping = array();

    public function __construct()
    {
        $this->mapping = [
            Offer::CALENDAR_TYPE_SINGLE =>
                [
                    //'lg' => new LargeTimestampsPlainTextFormatter(),
                    //'md' => new MediumTimestampsPlainTextFormatter(),
                    //'sm' => new SmallTimestampsPlainTextForatter(),
                    //'xs' => new ExtraSmallTimestampsPlainTextFormatter(),
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
                    'lg' => new LargePeriodicPlainTextFormatter(),
                    'md' => new MediumPeriodicPlainTextFormatter(),
                    'sm' => new SmallPeriodicPlainTextFormatter(),
                    'xs' => new ExtraSmallPeriodicPlainTextFormatter(),
                ],
            /*
            Offer::CALENDAR_TYPE_PERMANENT =>
                [
                    //'lg' => new LargePermanentHTMLFormatter(),
                ],
            */
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
