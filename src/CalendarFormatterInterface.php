<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Offer;

interface CalendarFormatterInterface
{
    public function format(Offer $offer, string $format): string;
}
