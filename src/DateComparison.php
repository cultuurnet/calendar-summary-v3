<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;

final class DateComparison
{
    public static function onSameDay(DateTimeImmutable $date1, DateTimeImmutable  $date2): bool
    {
        $date1 = $date1->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $date2 = $date2->setTimezone(new DateTimeZone(date_default_timezone_get()));

        return $date1->format('Y-m-d') === $date2->format('Y-m-d');
    }

    public static function isThisEvening(DateTimeImmutable $date): bool
    {
        return self::onSameDay($date, new DateTimeImmutable()) && (int)$date->format('G') >= 18;
    }

    public static function isToday(DateTimeImmutable $date): bool
    {
        return self::onSameDay($date, new DateTimeImmutable());
    }

    public static function isTomorrow(DateTimeImmutable $date): bool
    {
        return self::onSameDay($date, (new DateTimeImmutable())->add(new DateInterval('P1D')));
    }

    public static function isCurrentYear(DateTimeImmutable $date): bool
    {
        $date = $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        return $date->format('Y') === (new DateTimeImmutable())->format('Y');
    }

    public static function inTheFuture(DateTimeImmutable $date): bool
    {
        $date = $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $now = new DateTimeImmutable();
        return $date > $now;
    }
}
