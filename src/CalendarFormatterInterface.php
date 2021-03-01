<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface CalendarFormatterInterface
{
    public function format(Offer $offer, string $format): string;
}
