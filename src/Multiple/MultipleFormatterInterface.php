<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Offer\Offer;

interface MultipleFormatterInterface
{
    public function format(Offer $offer): string;
}
