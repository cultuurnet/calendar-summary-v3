<?php

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\SearchV3\ValueObjects\Offer;

use CultuurNet\CalendarSummaryV3\Periodic\ExtraSmallPeriodicHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\LargePeriodicHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\SmallPeriodicHTMLFormatter;
use CultuurNet\CalendarSummary\Permanent\LargePermanentHTMLFormatter;
use CultuurNet\CalendarSummary\Timestamps\ExtraSmallTimestampsHTMLFormatter;
use CultuurNet\CalendarSummary\Timestamps\LargeTimestampsHTMLFormatter;
use CultuurNet\CalendarSummary\Timestamps\MediumTimestampsHTMLFormatter;
use CultuurNet\CalendarSummary\Timestamps\SmallTimestampsHTMLFormatter;

class CalendarHTMLFormatter implements CalendarFormatterInterface
{
    protected $mapping = array();

    public function __construct()
    {
        $this->mapping = [
            Offer::CALENDAR_TYPE_SINGLE =>
                [
                    //'lg' => new LargeTimestampsHTMLFormatter(),
                    //'md' => new MediumTimestampsHTMLFormatter(),
                    //'sm' => new SmallTimestampsHTMLFormatter(),
                    //'xs' => new ExtraSmallTimestampsHTMLFormatter(),
                ],
            Offer::CALENDAR_TYPE_MULTIPLE =>
                [
                    //'lg' => new LargeTimestampsHTMLFormatter(),
                    //'md' => new MediumTimestampsHTMLFormatter(),
                    //'sm' => new SmallTimestampsHTMLFormatter(),
                    //'xs' => new ExtraSmallTimestampsHTMLFormatter(),
                ],
            Offer::CALENDAR_TYPE_PERIODIC =>
                [
                    'lg' => new LargePeriodicHTMLFormatter(),
                    'md' => new MediumPeriodicHTMLFormatter(),
                    'sm' => new SmallPeriodicHTMLFormatter(),
                    'xs' => new ExtraSmallPeriodicHTMLFormatter(),
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
