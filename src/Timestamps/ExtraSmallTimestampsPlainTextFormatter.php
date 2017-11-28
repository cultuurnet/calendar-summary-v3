<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 27/03/15
 * Time: 16:56
 */

namespace CultuurNet\CalendarSummaryV3\Timestamps;

use CultuurNet\CalendarSummary\FormatterException;
use IntlDateFormatter;

class ExtraSmallTimestampsPlainTextFormatter implements TimestampsFormatterInterface
{
    private $fmtDay;

    private $fmtMonth;

    public function __construct()
    {
        $this->fmtDay = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd'
        );

        $this->fmtMonth = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'MMM'
        );
    }

    public function format(
        \CultureFeed_Cdb_Data_Calendar_TimestampList $timestampList
    ) {
        $timestamps_count = iterator_count($timestampList);
        $timestampList->rewind();

        if ($timestamps_count == 1) {
            $timestamp = $timestampList->current();
            $dateFrom = $timestamp->getDate();

            $dateFromDay = $this->fmtDay->format(strtotime($dateFrom));
            $dateFromMonth = $this->fmtMonth->format(strtotime($dateFrom));
            $dateFromMonth = rtrim($dateFromMonth, ".");

            $output = $dateFromDay . ' ' . $dateFromMonth;

            return $output;
        } else {
            throw new FormatterException('xs format not supported for multiple timestamps.');
        }
    }
}
