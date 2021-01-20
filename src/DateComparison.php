<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;

final class DateComparison
{
    public static function onSameDay(DateTimeInterface $date1, DateTimeInterface  $date2): bool
    {
        $date1 = $date1->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $date2 = $date2->setTimezone(new DateTimeZone(date_default_timezone_get()));

        return $date1->format('Y-m-d') === $date2->format('Y-m-d');
    }

    public static function inTheFuture(DateTimeInterface $date): bool
    {
        $date = $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $now = new DateTimeImmutable();
        return $date > $now;
    }
}
