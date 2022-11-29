<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use DateTimeImmutable;

trait MediumPermanentWeekScheme
{
    public function getWeekScheme(array $weekDaysOpen, DateFormatter $formatter): array
    {
        // Do no assume people will order the days consecutive
        ksort($weekDaysOpen);
        $translatedDayNamesWithOpeningHours = [];
        $dayPeriod = '';
        $startNewPeriod = true;
        $isFirstPeriod = true;
        $isFirstPeriodMin3days = false;

        foreach ($weekDaysOpen as $weekDayNumber => $dayName) {
            // We start a new period, but the following day is closed
            if ($startNewPeriod && !array_key_exists($weekDayNumber + 1, $weekDaysOpen)) {
                $translatedDayNamesWithOpeningHours[] = $formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($dayName));
                // If this is the first run it cannot be min 3 days cause following day is closed.
                if ($isFirstPeriod) {
                    $isFirstPeriod = false;
                }
            }
            // Start a new period and the following day is open
            if ($startNewPeriod && array_key_exists($weekDayNumber + 1, $weekDaysOpen)) {
                $dayPeriod = $formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($dayName));
                $startNewPeriod = false;
                // If this is the first period, check if it is minimum 3 days.
                if ($isFirstPeriod && array_key_exists($weekDayNumber + 2, $weekDaysOpen)) {
                    $isFirstPeriodMin3days = true;
                }
                $isFirstPeriod = false;
            }
            // The previous day was open but the following day isn't
            if (!$startNewPeriod && !array_key_exists($weekDayNumber + 1, $weekDaysOpen)) {
                $dayPeriod .= ' - ' . $formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($dayName));
                $translatedDayNamesWithOpeningHours[] = $dayPeriod;
                $startNewPeriod = true;
                $dayPeriod = '';
            }
            // Do nothing if both the previous & following day are open
        }

        $translatedDayNamesWithOpeningHours[] = $isFirstPeriodMin3days;

        return $translatedDayNamesWithOpeningHours;
    }
}
