<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\Offer\Offer;

interface PeriodicFormatterInterface
{
    public function format(Offer $offer): string;
}
