<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 06/03/15
 * Time: 11:01
 */

namespace CultuurNet\CalendarSummary;

use CultuurNet\CalendarSummary\Period\ExtraSmallPeriodPlainTextFormatter;
use CultuurNet\CalendarSummary\Period\LargePeriodPlainTextFormatter;
use CultuurNet\CalendarSummary\Period\MediumPeriodPlainTextFormatter;
use CultuurNet\CalendarSummary\Period\SmallPeriodPlainTextFormatter;
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
                'lg' => new LargeTimestampsPlainTextFormatter(),
                'md' => new MediumTimestampsPlainTextFormatter(),
                'sm' => new SmallTimestampsPlainTextFormatter(),
                'xs' => new ExtraSmallTimestampsPlainTextFormatter(),
            ],
            Offer::CALENDAR_TYPE_MULTIPLE =>
                [
                    'lg' => new LargeTimestampsPlainTextFormatter(),
                    'md' => new MediumTimestampsPlainTextFormatter(),
                    'sm' => new SmallTimestampsPlainTextFormatter(),
                    'xs' => new ExtraSmallTimestampsPlainTextFormatter(),
                ],
            Offer::CALENDAR_TYPE_PERIODIC =>
            [
                'lg' => new LargePeriodPlainTextFormatter(),
                'md' => new MediumPeriodPlainTextFormatter(),
                'sm' => new SmallPeriodPlainTextFormatter(),
                'xs' => new ExtraSmallPeriodPlainTextFormatter(),
            ],
            \CultureFeed_Cdb_Data_Calendar_Permanent::class =>
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
