<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use IntlDateFormatter;

final class IntlDateFormatterFactory
{
    /**
     * @see https://unicode-org.github.io/icu/userguide/format_parse/datetime/
     */
    private const PATTERN_DAY_NUMBER = 'd'; // Example '1', '31'
    private const PATTERN_MONTH_NUMBER = 'M'; // Example '1', '12'

    /**
     * Used to format days as 1 - 31
     */
    public static function createDayNumberFormatter(string $langCode): IntlDateFormatter
    {
        return self::createFromPattern($langCode, self::PATTERN_DAY_NUMBER);
    }

    /**
     * Used to format months as 1 - 12
     */
    public static function createMonthNumberFormatter(string $langCode): IntlDateFormatter
    {
        return self::createFromPattern($langCode, self::PATTERN_MONTH_NUMBER);
    }

    private static function createFromPattern(string $langCode, string $pattern): IntlDateFormatter
    {
        return new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            $pattern
        );
    }
}
