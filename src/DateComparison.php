<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use Carbon\CarbonImmutable;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;

final class DateComparison
{
    public static function onSameDay(DateTimeImmutable $date1, DateTimeImmutable $date2): bool
    {
        $date1 = $date1->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $date2 = $date2->setTimezone(new DateTimeZone(date_default_timezone_get()));

        return CarbonImmutable::instance($date1)->isSameDay(
            CarbonImmutable::instance($date2)
        );
    }

    public static function isThisEvening(DateTimeImmutable $date): bool
    {
        $date = $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        return self::onSameDay($date, new CarbonImmutable()) && (int)$date->format('G') >= 18;
    }

    public static function isToday(DateTimeImmutable $date): bool
    {
        return self::onSameDay($date, new CarbonImmutable());
    }

    public static function isTomorrow(DateTimeImmutable $date): bool
    {
        return self::onSameDay($date, (new CarbonImmutable())->add(new DateInterval('P1D')));
    }

    public static function isCurrentWeek(DateTimeImmutable $date): bool
    {
        $date = $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        return CarbonImmutable::instance($date)->isCurrentWeek();
    }

    public static function isCurrentYear(DateTimeImmutable $date): bool
    {
        $date = $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        return CarbonImmutable::instance($date)->isCurrentYear();
    }

    public static function inTheFuture(DateTimeImmutable $date): bool
    {
        $date = $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $now = new DateTimeImmutable();
        return $date > $now;
    }
}
