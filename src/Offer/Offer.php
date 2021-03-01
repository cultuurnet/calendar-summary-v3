<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

final class Offer
{
    public static function fromJsonLd(string $json): self
    {
        return new self();
    }
}
