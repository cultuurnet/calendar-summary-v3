<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTime;
use IntlDateFormatter;

/**
 * Provide a large plain text formatter for periodic calendar type.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class LargePeriodicPlainTextFormatter extends LargePeriodicFormatter implements PeriodicFormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format(Offer $offer)
    {
        $output = $this->generateDates($offer->getStartDate(), $offer->getEndDate());

        if ($offer->getOpeningHours()) {
            $output .= PHP_EOL . $this->generateWeekScheme($offer->getOpeningHours());
        }

        return $output;
    }

    /**
     * @param $time
     * @return string
     */
    protected function getFormattedTime($time)
    {
        $formattedShortTime = ltrim($time, '0');
        if ($formattedShortTime == ':00') {
            $formattedShortTime = '0:00';
        }
        return $formattedShortTime;
    }

    /**
     * @param DateTime $dateFrom
     * @param DateTime $dateTo
     * @return string
     */
    protected function generateDates(DateTime $dateFrom, DateTime $dateTo)
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlDateTo = $this->fmt->format($dateTo);

        $output_dates =  'Van ' . $intlDateFrom . ' tot ' . $intlDateTo;
        return $output_dates;
    }

    /**
     * @param $openingHoursData
     * @return string
     */
    protected function generateWeekScheme($openingHoursData)
    {
        $outputWeek = '(';

        // Create an array with formatted days.
        $formattedDays = [];
        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDaysOfWeek() as $dayOfWeek) {
                if (!isset($formattedDays[$dayOfWeek])) {
                    $formattedDays[$dayOfWeek] = $this->mappingDays[$dayOfWeek]
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
        foreach (array_keys($this->mappingDays) as $day) {
            $closedDays[$day] = $this->mappingDays[$day] . ' gesloten,' . PHP_EOL;
        }
        // Merge the formatted days with the closed days array to fill in missing days and sort using the days mapping.
        $formattedDays = array_replace($this->mappingDays, $formattedDays + $closedDays);

        // Render the rest of the week scheme output.
        foreach ($formattedDays as $formattedDay) {
            $outputWeek .= $formattedDay;
        }
        $outputWeek = rtrim($outputWeek, ',' . PHP_EOL);
        return $outputWeek . ')';
    }
}
