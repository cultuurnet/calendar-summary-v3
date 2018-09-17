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
        $output = $this->generateDates(
            $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get())),
            $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()))
        );

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

        $output_dates =  ucfirst($this->trans->getTranslations()->t('from')) . ' ';
        $output_dates .= $intlDateFrom . ' ' . $this->trans->getTranslations()->t('till') . ' ' . $intlDateTo;
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
                $translatedDay = $this->fmtDays->format(strtotime($dayOfWeek));

                if (!isset($formattedDays[$dayOfWeek])) {
                    $formattedDays[$dayOfWeek] = $translatedDay
                        . ' ' . $this->trans->getTranslations()->t('from') . ' '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' ' . $this->trans->getTranslations()->t('till') . ' '
                        . $this->getFormattedTime($openingHours->getCloses())
                        . ', '
                        . PHP_EOL;
                } else {
                    $formattedDays[$dayOfWeek] .= $this->trans->getTranslations()->t('from') . ' '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' ' . $this->trans->getTranslations()->t('till') . ' '
                        . $this->getFormattedTime($openingHours->getCloses())
                        . ', '
                        . PHP_EOL;
                }
            }
        }
        // Create an array with formatted closed days.
        $closedDays = [];
        foreach ($this->daysOfWeek as $day) {
            $closedDays[$day] = $this->fmtDays->format(strtotime($day)) . ' '
                . $this->trans->getTranslations()->t('closed') . ',' . PHP_EOL;
        }
        // Merge the formatted days with the closed days array and sort them.
        $sortedDays = array();
        foreach ($this->daysOfWeek as $day) {
            if (isset($formattedDays[$day])) {
                $sortedDays[$day] = $formattedDays[$day];
            } else {
                $sortedDays[$day] = $closedDays[$day];
            }
        }

        // Render the rest of the week scheme output.
        foreach ($sortedDays as $sortedDay) {
            $outputWeek .= $sortedDay;
        }
        $outputWeek = rtrim($outputWeek, ', ' . PHP_EOL);
        return $outputWeek . ')';
    }
}
