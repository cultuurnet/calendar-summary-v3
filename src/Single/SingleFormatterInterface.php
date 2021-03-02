<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Offer\Offer;

interface SingleFormatterInterface
{
    public function format(Offer $offer): string;
}
