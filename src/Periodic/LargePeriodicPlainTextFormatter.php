<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use DateTime;
use IntlDateFormatter;

class LargePeriodicPlainTextFormatter implements PeriodicFormatterInterface
{

    /**
     * Translate the day to short Dutch format.
     */
    protected $mapping_days = array(
        'monday' => 'ma',
        'tuesday' => 'di',
        'wednesday' => 'wo',
        'thursday' => 'do',
        'friday' => 'vr',
        'saturday' => 'za',
        'sunday' => 'zo',
    );


    public function format($place)
    {
        $output = $this->generateDates($place->getStartDate(), $place->getEndDate());

        if ($place->getOpeningHours()) {
            $output .= PHP_EOL . $this->generateWeekScheme($place->getOpeningHours());
        }

        return $output;
    }

    protected function getFormattedTime($time)
    {
        $formatted_short_time = ltrim($time, '0');
        if ($formatted_short_time == ':00') {
            $formatted_short_time = '0:00';
        }
        return $formatted_short_time;
    }

    protected function generateDates(DateTime $dateFrom, DateTime $dateTo)
    {
        $fmt = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );

        $dateFromTimestamp = $dateFrom->getTimestamp();
        $intlDateFrom =$fmt->format($dateFromTimestamp);

        $dateToTimestamp = $dateTo->getTimestamp();
        $intlDateTo = $fmt->format($dateToTimestamp);

        $output_dates =  'Van ' . $intlDateFrom . ' tot ' . $intlDateTo;
        return $output_dates;
    }

    protected function generateWeekScheme($openingHoursData)
    {
        $output_week = '(';

        // Create an array with formatted days.
        $formattedDays = [];
        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDayOfWeek() as $dayOfWeek) {
                if (!isset($formattedDays[$dayOfWeek])) {
                    $formattedDays[$dayOfWeek] = $this->mapping_days[$dayOfWeek]
                        . ' van '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' tot ' . $this->getFormattedTime($openingHours->getCloses())
                        . PHP_EOL;
                } else {
                    $formattedDays[$dayOfWeek] .= 'van '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' tot ' . $this->getFormattedTime($openingHours->getCloses())
                        . ','
                        . PHP_EOL;
                }
            }
        }

        // Create an array with formatted closed days.
        $closedDays = [];
        foreach (array_keys($this->mapping_days) as $day) {
            $closedDays[$day] = $this->mapping_days[$day] . '  gesloten,' . PHP_EOL;
        }

        // Merge the formatted days with the closed days array to fill in missing days and sort using the days mapping.
        $formattedDays = array_replace($this->mapping_days, $formattedDays + $closedDays);

        // Render the rest of the week scheme output.
        foreach ($formattedDays as $formattedDay) {
            $output_week .= $formattedDay;
        }
        $output_week = rtrim($output_week, ',' . PHP_EOL);
        return $output_week . ')';
    }
}
