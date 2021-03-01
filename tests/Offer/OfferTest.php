<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use PHPStan\Testing\TestCase;

final class OfferTest extends TestCase
{
    public function testCanCreateFromJsonLd(): void
    {
        $jsonLd = '{"calendarType":"single"}';
        $expected = new Offer(
            CalendarType::single()
        );

        $this->assertEquals($expected, Offer::fromJsonLd($jsonLd));
    }
}
