<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;

/**
 * Provide a large plain text formatter for permanent calendar type.
 * @package CultuurNet\CalendarSummaryV3\Permanent
 */
class LargePermanentPlainTextFormatter extends LargePermanentFormatter implements PermanentFormatterInterface
{
    public function format(Offer $offer): string
    {
        $output = '';
        if ($offer->getOpeningHours()) {
            $output .= $this->generateWeekScheme($offer->getOpeningHours());
        } else {
            $output .= ucfirst($this->trans->getTranslations()->t('always_open')) . PHP_EOL;
        }
        return $output;
    }

    protected function getFormattedTime(string $time): string
    {
        $formattedShortTime = ltrim($time, '0');
        if ($formattedShortTime == ':00') {
            $formattedShortTime = '0:00';
        }
        return $formattedShortTime;
    }

    /**
     * @param OpeningHours[] $openingHoursData
     * @return string
     */
    protected function generateWeekScheme(array $openingHoursData): string
    {
        $outputWeek = '';
        // Create an array with formatted days.
        $formattedDays = [];

        foreach ($openingHoursData as $openingHours) {
            $daysOfWeek = $openingHours->getDaysOfWeek();
            foreach ($daysOfWeek as $i => $dayOfWeek) {
                $translatedDay = $this->fmtShortDays->format(strtotime($dayOfWeek));
                //$daysOfWeek[$i] = $this->fmtDays->format(strtotime($dayOfWeek));

                if (!isset($formattedDays[$dayOfWeek])) {
                    $formattedDays[$dayOfWeek] = $translatedDay
                        . ' ' . $this->trans->getTranslations()->t('from') . ' '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' ' . $this->trans->getTranslations()->t('till') . ' '
                        . $this->getFormattedTime($openingHours->getCloses())
                        . PHP_EOL;
                } else {
                    $formattedDays[$dayOfWeek] .= '' . $this->trans->getTranslations()->t('from') . ' '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' ' . $this->trans->getTranslations()->t('till') . ' '
                        . $this->getFormattedTime($openingHours->getCloses())
                        . PHP_EOL;
                }
            }
        }

        // Create an array with formatted closed days.
        $closedDays = [];
        foreach ($this->daysOfWeek as $day) {
            $closedDays[$day] = $this->fmtShortDays->format(strtotime($day)) . ' '
                . $this->trans->getTranslations()->t('closed') . PHP_EOL;
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
        return $outputWeek;
    }
}
