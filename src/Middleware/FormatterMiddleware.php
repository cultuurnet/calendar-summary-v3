<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Middleware;

use Closure;
use CultuurNet\CalendarSummaryV3\Offer\Offer;

interface FormatterMiddleware
{
    public function format(Offer $offer, Closure $next): string;
}
