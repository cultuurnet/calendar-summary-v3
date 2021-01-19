<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;

/**
 * Provides an interface for periodic formatter.
 */
interface PeriodicFormatterInterface
{
    public function format(Offer $offer): string;
}
