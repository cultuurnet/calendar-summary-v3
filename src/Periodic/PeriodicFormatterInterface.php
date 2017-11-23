<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\Place;

interface PeriodicFormatterInterface
{

    /**
     * @param Offer|Place $offer
     * @return string
     */
    public function format($offer);
}
