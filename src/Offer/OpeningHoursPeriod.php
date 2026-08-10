<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use DateTimeImmutable;

/**
 * A period during which the regular opening hours do not apply.
 */
interface OpeningHoursPeriod
{
    public function getStartDate(): DateTimeImmutable;

    public function getEndDate(): DateTimeImmutable;

    public function getDescriptionForLanguage(string $languageCode): string;
}
