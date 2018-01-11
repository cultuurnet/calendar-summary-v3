<?php

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface CalendarFormatterInterface
{

    /**
     * Format the given offer in the given format.
     *
     * @param Offer $offer
     * @param $format
     **/
    public function format(Offer $offer, $format);
}
