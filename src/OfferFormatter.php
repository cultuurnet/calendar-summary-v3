<?php


namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface OfferFormatter
{
    public function format(Offer $offer): string;
}
