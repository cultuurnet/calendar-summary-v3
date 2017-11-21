<?php

namespace CultuurNet\CalendarSummary;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface CalendarFormatterInterface
{
    public function format(Offer $offer, $format);
}
