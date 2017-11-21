<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 06/03/15
 * Time: 14:38
 */

namespace CultuurNet\CalendarSummary\Permanent;

use \CultureFeed_Cdb_Data_Calendar_SchemeDay as SchemeDay;

class LargePermanentPlainTextFormatter implements PermanentFormatterInterface
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
        \CultureFeed_Cdb_Data_Calendar_Permanent $permanent
    ) {
        $output = '';
        if (!is_null($permanent->getWeekScheme())) {
            $output .= $this->generateWeekscheme($permanent->getWeekScheme());
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

    protected function generateWeekscheme($weekscheme)
    {
        $output_week = '';

        $keys = array_keys($weekscheme->getDays());

        for ($i = 0; $i <= 6; $i++) {
            $one_day = $weekscheme->getDays()[$keys[$i]];
            if (!is_null($one_day)) {
                if ($one_day->getOpenType()==SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN) {
                    $output_week .= $this->getDutchDay($keys[$i]) . ' ';
                    foreach ($one_day->getOpeningTimes() as $opening_time) {
                        $output_week .= 'Van ' . $this->getFormattedTime($opening_time->getOpenFrom());
                        if (!is_null($opening_time->getOpenTill())) {
                            $output_week .= ' tot ' . $this->getFormattedTime($opening_time->getOpenTill());
                        }
                        $output_week .= PHP_EOL;
                    }
                }
            } elseif (!is_null($one_day) && $one_day->getOpenType()==SchemeDay::SCHEMEDAY_OPEN_TYPE_BY_APPOINTMENT) {
                $output_week .= $this->getDutchDay($keys[$i]) . ' ';
                $output_week .= ' op afspraak' . PHP_EOL;
            } else {
                $output_week .= $this->getDutchDay($keys[$i]) . ' ';
                $output_week .= ' gesloten' . PHP_EOL;
            }
        }

        return $output_week;
    }
}
