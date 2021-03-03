<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\Offer\Offer;

interface PermanentFormatterInterface
{
    public function format(Offer $offer): string;
}
