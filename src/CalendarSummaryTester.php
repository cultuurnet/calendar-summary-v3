<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use Carbon\CarbonImmutable;

final class CalendarSummaryTester
{
    public static function setTestNow($year = 0, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0, $tz = null)
    {
        CarbonImmutable::setTestNow(CarbonImmutable::create($year, $month, $day, $hour, $minute, $second, $tz));
    }
}
