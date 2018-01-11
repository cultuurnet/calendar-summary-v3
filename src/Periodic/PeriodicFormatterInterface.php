<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;

/**
 * Provides an interface for periodic formatter.
 */
interface PeriodicFormatterInterface
{

    /**
     * Return formatted period string.
     *
     * @param \CultuurNet\SearchV3\ValueObjects\Offer $offer
     *
     * @return string
     */
    public function format(Offer $offer);
}
