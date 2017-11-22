<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface PeriodicFormatterInterface
{

    /**
     * @param Offer $offer
     * @return string
     */
    public function format(Offer $offer);
}
