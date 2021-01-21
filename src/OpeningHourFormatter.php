<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

final class OpeningHourFormatter
{
    /**
     * Removes the leading 0 from the hour.
     * For example 00:15 -> 0:15, 01:00 -> 1:00, etc.
     *
     * @param string $openingHour
     * @return string
     */
    public static function format(string $openingHour): string
    {
        $timeParts = explode(':', $openingHour);

        $hour = ltrim($timeParts[0], '0');
        if ($hour === '') {
            $hour = '0';
        }

        $timeParts[0] = $hour;
        return implode(':', $timeParts);
    }
}
