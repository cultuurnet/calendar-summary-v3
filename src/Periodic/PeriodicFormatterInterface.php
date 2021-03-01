<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface PeriodicFormatterInterface
{
    public function format(Offer $offer): string;
}
