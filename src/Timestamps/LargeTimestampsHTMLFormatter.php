<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 06/03/15
 * Time: 14:06
 */

namespace CultuurNet\CalendarSummary\Timestamps;

use IntlDateFormatter;

class LargeTimestampsHTMLFormatter implements TimestampsFormatterInterface
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
     * @param \CultureFeed_Cdb_Data_Calendar_TimestampList $timestampList
     * @return string
     */
    public function format(
        \CultureFeed_Cdb_Data_Calendar_TimestampList $timestampList
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
     * @param \CultureFeed_Cdb_Data_Calendar_Timestamp $timestamp
     * @return string
     */
    private function formatSingleTimestamp(
        \CultureFeed_Cdb_Data_Calendar_Timestamp $timestamp
    ) {
        $date = $timestamp->getDate();
        $intlDate = $this->fmt->format(strtotime($date));
        $intlWeekDay = $this->fmtWeekDayLong->format(strtotime($date));
        $startTime = $timestamp->getStartTime();
        $intlStartTime = $this->fmtTime->format(strtotime($startTime));
        $endTime = $timestamp->getEndTime();
        $intlEndTime = $this->fmtTime->format(strtotime($endTime));

        if (!empty($startTime)) {
            $output = '<time itemprop="startDate" datetime="' . $date . 'T' . $intlStartTime . '">';
        } else {
            $output = '<time itemprop="startDate" datetime="' . $date . $intlStartTime . '">';
        }
        $output .= '<span class="cf-weekday cf-meta">' . $intlWeekDay . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDate . '</span>';

        if (!empty($startTime)) {
            $output .= ' ';
            if (!empty($endTime)) {
                $output .= '<span class="cf-from cf-meta">van</span>';
                $output .= ' ';
            } else {
                $output .= '<span class="cf-from cf-meta">om</span>';
                $output .= ' ';
            }
            $output .= '<span class="cf-time">' . $intlStartTime . '</span>';
            $output .= '</time>';
            if (!empty($endTime)) {
                $output .= ' ';
                $output .= '<span class="cf-to cf-meta">tot</span>';
                $output .= ' ';
                $output .= '<time itemprop="endDate" datetime="' . $date . 'T' . $intlEndTime . '">';
                $output .= '<span class="cf-time">' . $intlEndTime . '</span>';
                $output .= '</time>';
            }
        } else {
            $output .= ' </time>';
        }

        return $output;
    }

    /**
     * @param \CultureFeed_Cdb_Data_Calendar_TimestampList $timestampList
     * @param int $timestamps_count
     * @return string
     */
    private function formatMultipleTimestamps(
        \CultureFeed_Cdb_Data_Calendar_TimestampList $timestampList,
        $timestamps_count
    ) {
        $showFrom = $this->getShowFrom();

        $output = '<ul class="list-unstyled">';

        // keep track of the active period and when the last one started, zero means none.
        $activePeriodIndex = 0;
        $lastPeriodStartDate = null;
        $lastPeriodStartTime = null;

        for ($i = 0; $i < $timestamps_count; $i++) {
            /** @var \CultureFeed_Cdb_Data_Calendar_Timestamp $timestamp */
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
                $output .= '<li>';
                if (!empty($startTime)) {
                    $output .= '<time itemprop="startDate" datetime="' . $date . 'T' . $intlStartTime . '">';
                } else {
                    $output .= '<time itemprop="startDate" datetime="' . $date . $intlStartTime . '">';
                }
                $output .= '<span class="cf-weekday cf-meta">' . $intlWeekDay . '</span>';
                $output .= ' ';
                $output .= '<span class="cf-date">' . $intlDate . '</span>';

                if (!empty($startTime)) {
                    if (!empty($endTime)) {
                        $output .= ' ';
                        $output .= '<span class="cf-from cf-meta">van</span>';
                    } else {
                        $output .= ' ';
                        $output .= '<span class="cf-from cf-meta">om</span>';
                    }
                    $output .= ' ';
                    $output .= '<span class="cf-time">' . $intlStartTime . '</span>';
                    $output .= '</time>';
                    if (!empty($endTime)) {
                        $output .= ' ';
                        $output .= '<span class="cf-to cf-meta">tot</span>';
                        $output .= ' ';
                        $output .= '<time itemprop="endDate" datetime="' . $endDate . 'T' . $intlEndTime . '">';
                        if ($activePeriodIndex > 0) {
                            $output .= '<span class="cf-weekday cf-meta">' . $intlEndWeekDay . '</span>';
                            $output .= ' ';
                            $output .= '<span class="cf-date">' . $intlEndDate . '</span>';
                            $output .= ' ';
                        }
                        $output .= '<span class="cf-time">' . $intlEndTime . '</span>';
                        $output .= '</time>';
                    }
                } else {
                    $output .= ' </time>';
                }

                $output .= '</li>';
            }

            $timestampList->next();
        }

        $output .= '</ul>';

        return $output;
    }
}
