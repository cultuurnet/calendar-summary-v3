<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 06/03/15
 * Time: 14:15
 */

namespace CultuurNet\CalendarSummaryV3\Periodic;

use \CultureFeed_Cdb_Data_Calendar_SchemeDay as SchemeDay;
use IntlDateFormatter;

class LargePeriodicPlainTextFormatter implements PeriodicFormatterInterface
{

    /**
     * Translate the day in short Dutch.
     */
    protected $mapping_days = array(
        'monday' => 'Ma',
        'tuesday' => 'Di',
        'wednesday' => 'Wo',
        'thursday' => 'Do',
        'friday' => 'Vr',
        'saturday' => 'Za',
        'sunday' => 'Zo',
    );


    public function format(
        \CultureFeed_Cdb_Data_Calendar_PeriodList $periodList
    ) {
        $period = $periodList->current();
        $output = $this->generateDates($period->getDateFrom(), $period->getDateTo());
        if (!is_null($period->getWeekScheme())) {
            $output .= PHP_EOL . $this->generateWeekscheme($period->getWeekScheme());
        }

        return $output;
    }

    protected function getDutchDay($day)
    {
        return $this->mapping_days[$day];
    }

    protected function getFormattedTime($time)
    {
        $formatted_time = substr($time, 0, -3);
        $formatted_short_time = ltrim($formatted_time, '0');
        if ($formatted_short_time == ':00') {
            $formatted_short_time = '0:00';
        }
        return $formatted_short_time;
    }

    protected function getEarliestTime($times)
    {
        $start_time = null;
        foreach ($times as $time) {
            if ($start_time==null || $start_time > $time->getOpenFrom()) {
                $start_time = $time->getOpenFrom();
            }
        }
        if (is_null($start_time)) {
            return '';
        } else {
            return ' ' . $this->getFormattedTime($start_time);
        }
    }

    protected function getLatestTime($times)
    {
        $end_time = null;
        foreach ($times as $time) {
            if ($end_time==null || $end_time < $time->getOpenTill()) {
                $end_time = $time->getOpenTill();
            }
        }
        if (is_null($end_time)) {
            return '';
        } else {
            return '-' . $this->getFormattedTime($end_time);
        }
    }

    protected function generateDates($date_from, $date_to)
    {
        $fmt = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );
        $dateFromString = $date_from;
        $dateFrom = strtotime($dateFromString);
        $intlDateFrom =$fmt->format($dateFrom);

        $dateToString =$date_to;
        $dateTo = strtotime($dateToString);
        $intlDateTo = $fmt->format($dateTo);

        $output_dates =  'Van ' . $intlDateFrom . ' tot ' . $intlDateTo;
        return $output_dates;
    }

    protected function generateWeekscheme($weekscheme)
    {
        $output_week = '';

        $keys = array_keys($weekscheme->getDays());

        for ($i = 0; $i <= 6; $i++) {
            $one_day = $weekscheme->getDays()[$keys[$i]];
            if ($i == 0) {
                $output_week .= '(';
            }
            if (!is_null($one_day)) {
                if ($one_day->getOpenType() == SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN) {
                    $output_week .= strtolower($this->getDutchDay($keys[$i])) . ' ';
                    $count = 1;
                    foreach ($one_day->getOpeningTimes() as $opening_time) {
                        $openingtimes = $one_day->getOpeningTimes();
                        $count_openingtimes = count($openingtimes);
                        $output_week .= 'van ' . $this->getFormattedTime($opening_time->getOpenFrom());
                        if (!is_null($opening_time->getOpenTill())) {
                            $output_week .= ' tot ' . $this->getFormattedTime($opening_time->getOpenTill());
                        }
                        if ($i == 6) {
                            $output_week .= ')' . PHP_EOL;
                        } else {
                            if ($count == $count_openingtimes) {
                                $output_week .= ',' . PHP_EOL;
                            } else {
                                $output_week .= PHP_EOL;
                            }
                        }
                        $count++;
                    }
                }
            } elseif (!is_null($one_day) && $one_day->getOpenType()==SchemeDay::SCHEMEDAY_OPEN_TYPE_BY_APPOINTMENT) {
                $output_week .= strtolower($this->getDutchDay($keys[$i])) . ' ';
                if ($i == 6) {
                    $output_week .= ' op afspraak)';
                } else {
                    $output_week .= ' op afspraak,' . PHP_EOL;
                }
            } else {
                $output_week .= strtolower($this->getDutchDay($keys[$i])) . ' ';
                if ($i == 6) {
                    $output_week .= ' gesloten)';
                } else {
                    $output_week .= ' gesloten,' . PHP_EOL;
                }
            }
        }

        return $output_week;
    }
}
