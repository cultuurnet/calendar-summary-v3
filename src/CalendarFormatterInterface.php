<?php

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface CalendarFormatterInterface
{
    public function format(Offer $offer, string $format): string;
}
