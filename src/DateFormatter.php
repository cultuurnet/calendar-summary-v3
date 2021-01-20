<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use DateTimeInterface;
use IntlDateFormatter;

final class DateFormatter
{
    /**
     * @see https://unicode-org.github.io/icu/userguide/format_parse/datetime/
     */
    private const PATTERN_DAY_NUMBER = 'd'; // Example '1', '31'
    private const PATTERN_MONTH_NUMBER = 'M'; // Example '1', '12'

    private const PATTERN_DAY_OF_WEEK = 'EEEE'; // Example 'tuesday'
    private const PATTERN_DAY_OF_WEEK_ABBREVIATED = 'EEE'; // Example 'tue'

    private const PATTERN_MONTH_NAME_ABBREVIATED = 'MMM'; // Example 'sep.'

    private const PATTERN_TIME = 'HH:mm'; // Example '01:05', '12:15'
    private const PATTERN_FULL_DATE = 'd MMMM yyyy'; // Example '1 january 2021'

    /**
     * @var string
     */
    private $langCode;

    public function __construct(string $langCode)
    {
        $this->langCode = $langCode;
    }

    /**
     * Used to format days as 1 - 31
     */
    public function formatAsDayNumber(DateTimeInterface $dateTime): string
    {
        return $this->format($dateTime, self::PATTERN_DAY_NUMBER);
    }

    /**
     * Used to format months as 1 - 12
     */
    public function formatAsMonthNumber(DateTimeInterface $dateTime): string
    {
        return $this->format($dateTime, self::PATTERN_MONTH_NUMBER);
    }

    /**
     * Used to format days as 'monday', 'tuesday', ...
     */
    public function formatAsDayOfWeek(DateTimeInterface $dateTime): string
    {
        return $this->format($dateTime, self::PATTERN_DAY_OF_WEEK);
    }

    /**
     * Used to format days as 'mon', 'tue', ...
     */
    public function formatAsAbbreviatedDayOfWeek(DateTimeInterface $dateTime): string
    {
        return $this->format($dateTime, self::PATTERN_DAY_OF_WEEK_ABBREVIATED);
    }

    /**
     * Used to format months as 'sep'
     */
    public function formatAsAbbreviatedMonthName(DateTimeInterface $dateTime): string
    {
        return rtrim($this->format($dateTime, self::PATTERN_MONTH_NAME_ABBREVIATED), '.');
    }

    /**
     * Used to format time as '01:05', '12:15', ...
     */
    public function formatAsTime(DateTimeInterface $dateTime): string
    {
        return $this->format($dateTime, self::PATTERN_TIME);
    }

    /**
     * Used to format dates as '01 november 2021'
     */
    public function formatAsFullDate(DateTimeInterface $dateTime): string
    {
        return $this->format($dateTime, self::PATTERN_FULL_DATE);
    }

    private function format(DateTimeInterface $dateTime, string $pattern): string
    {
        $formatter = new IntlDateFormatter(
            $this->langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            $pattern
        );
        return $formatter->format($dateTime);
    }
}
