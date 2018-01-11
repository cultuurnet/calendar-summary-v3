<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface PermanentFormatterInterface
{

    /**
     * Format the given offer with permanent info.
     */
    public function format(Offer $offer);
}
