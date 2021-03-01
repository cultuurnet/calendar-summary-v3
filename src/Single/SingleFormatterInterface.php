<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface SingleFormatterInterface
{
    public function format(Offer $offer): string;
}
