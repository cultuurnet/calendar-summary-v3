<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use Carbon\CarbonImmutable;
use DateInterval;
use DateTimeImmutable;

final class DateComparison
{
    public static function onSameDay(DateTimeImmutable $date1, DateTimeImmutable $date2): bool
    {
        return CarbonImmutable::instance($date1)->isSameDay(
            CarbonImmutable::instance($date2)
        );
    }

    public static function isThisEvening(DateTimeImmutable $date): bool
    {
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
        return self::inTheFuture($date) && CarbonImmutable::instance($date)->isCurrentWeek();
    }

    public static function isCurrentYear(DateTimeImmutable $date): bool
    {
        return CarbonImmutable::instance($date)->isCurrentYear();
    }

    public static function inTheFuture(DateTimeImmutable $date): bool
    {
        $now = new CarbonImmutable();
        return $date > $now;
    }
}
