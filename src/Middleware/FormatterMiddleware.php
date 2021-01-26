<?php

namespace CultuurNet\CalendarSummaryV3\Middleware;

use Closure;
use CultuurNet\SearchV3\ValueObjects\Offer;

interface FormatterMiddleware
{
    public function format(Offer $offer, Closure $next): string;
}
