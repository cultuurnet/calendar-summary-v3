<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface PermanentFormatterInterface
{
    public function format(Offer $offer): string;
}
