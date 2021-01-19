<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface SingleFormatterInterface
{
    public function format(Offer $offer): string;
}
