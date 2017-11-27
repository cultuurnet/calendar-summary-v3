<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface PermanentFormatterInterface
{

    /**
     * @param Offer $offer
     * @return string
     */
    public function format(Offer $offer);
}
