<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 27/03/15
 * Time: 17:42
 */

namespace CultuurNet\CalendarSummaryV3\Timestamps;

use CultureFeed_Cdb_Data_Calendar_Timestamp;
use CultureFeed_Cdb_Data_Calendar_TimestampList;
use IntlDateFormatter;

class LargeTimestampsPlainTextFormatter
{
    use ShowFrom;

    private $fmt;

    private $fmtWeekDayLong;

    private $fmtWeekDayShort;

    private $fmtTime;

    public function __construct()
    {
        $this->fmt = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );

        $this->fmtWeekDayLong = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'EEEE'
        );

        $this->fmtWeekDayShort = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'EEEEEE'
        );

        $this->fmtTime = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'HH:mm'
        );
    }

    /**
     * @param CultureFeed_Cdb_Data_Calendar_TimestampList $timestampList
     * @return string
     */
    public function format(
        CultureFeed_Cdb_Data_Calendar_TimestampList $timestampList
    ) {
        $timestamps_count = iterator_count($timestampList);
        $timestampList->rewind();

        if ($timestamps_count == 1) {
            $timestamp = $timestampList->current();
            return $this->formatSingleTimestamp($timestamp);
        } else {
            return $this->formatMultipleTimestamps($timestampList, $timestamps_count);
        }
    }

    /**
     * @param CultureFeed_Cdb_Data_Calendar_Timestamp $timestamp
     * @return string
     */
    private function formatSingleTimestamp(
        CultureFeed_Cdb_Data_Calendar_Timestamp $timestamp
    ) {
        $date = $timestamp->getDate();
        $intlDate = $this->fmt->format(strtotime($date));
        $intlWeekDay = $this->fmtWeekDayLong->format(strtotime($date));
        $startTime = $timestamp->getStartTime();
        $intlStartTime = $this->fmtTime->format(strtotime($startTime));
        $endTime = $timestamp->getEndTime();
        $intlEndTime = $this->fmtTime->format(strtotime($endTime));

        $output = $intlWeekDay . ' ' . $intlDate . PHP_EOL;
        if (!empty($endTime)) {
            $output .= 'van ';
        } else {
            $output .= 'om ';
        }
        $output .= $intlStartTime;
        if (!empty($endTime)) {
            $output .= ' tot ' . $intlEndTime;
        }

        return $output;
    }

    /**
     * @param CultureFeed_Cdb_Data_Calendar_TimestampList $timestampList
     * @param int $timestamps_count
     * @return string
     */
    private function formatMultipleTimestamps(
        CultureFeed_Cdb_Data_Calendar_TimestampList $timestampList,
        $timestamps_count
    ) {
        $showFrom = $this->getShowFrom();
        $output = '';

        // keep track of the active period and when the last one started, zero means none.
        $activePeriodIndex = 0;
        $lastPeriodStartDate = null;
        $lastPeriodStartTime = null;

        for ($i = 0; $i < $timestamps_count; $i++) {
            /** @var CultureFeed_Cdb_Data_Calendar_Timestamp $timestamp */
            $timestamp = $timestampList->current();
            $date = $timestamp->getDate();
            $endDate = $timestamp->getEndDate();
            $seconds = intval(substr($timestamp->getStartTime(), 6, 2));
            $endTime = $timestamp->getEndTime();
            $startTime = $timestamp->getStartTime();
            $intlStartTime = $this->fmtTime->format(strtotime($startTime));

            if ($seconds > 0 && $seconds !== $activePeriodIndex) {
                $lastPeriodStartDate = $date;
                $lastPeriodStartTime = $intlStartTime;
            }

            $activePeriodIndex = $seconds;

            if ($activePeriodIndex > 0) {
                if (empty($endTime)) {
                    $timestampList->next();
                    continue;
                } else {
                    $date = $lastPeriodStartDate;
                    $intlStartTime = $lastPeriodStartTime;
                }
            }

            $intlDate = $this->fmt->format(strtotime($date));
            $intlWeekDay = $this->fmtWeekDayShort->format(strtotime($date));

            $intlEndDate = $this->fmt->format(strtotime($endDate));
            $intlEndWeekDay = $this->fmtWeekDayShort->format(strtotime($endDate));
            $intlEndTime = $this->fmtTime->format(strtotime($endTime));

            if (strtotime($date) >= $showFrom) {
                $output = empty($output) ? $output : $output . PHP_EOL;
                if ($activePeriodIndex === 0) {
                    $output .= $intlWeekDay . ' ' . $intlDate . PHP_EOL;
                    if (!empty($endTime)) {
                        $output .= 'van ';
                    } else {
                        $output .= 'om ';
                    }
                    $output .= $intlStartTime;
                    if (!empty($endTime)) {
                        $output .= ' tot ' . $intlEndTime;
                    }
                } else {
                    $output .= 'Van ' . $intlWeekDay . ' ' . $intlDate . ' ' . $intlStartTime . PHP_EOL;
                    $output .= 'tot ' . $intlEndWeekDay . ' ' . $intlEndDate . ' ' . $intlEndTime;
                }
            }

            $timestampList->next();
        }

        return $output;
    }
}
