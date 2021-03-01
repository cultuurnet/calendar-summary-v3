<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use DateTimeImmutable;
use PHPStan\Testing\TestCase;

final class OfferTest extends TestCase
{
    public function testCanCreateFromJsonLd(): void
    {
        $jsonLd = file_get_contents(__DIR__ . '/data/offer.json');
        $expected = new Offer(
            OfferType::event(),
            CalendarType::single(),
            new DateTimeImmutable('2021-03-01T23:00:00+00:00'),
            new DateTimeImmutable('2021-03-28T22:59:59+00:00')
        );

        $this->assertEquals($expected, Offer::fromJsonLd($jsonLd));
    }

    public function testCanParsePermanentOffer(): void
    {
        $jsonLd = file_get_contents(__DIR__ . '/data/permanent.json');
        $expected = new Offer(
            OfferType::event(),
            CalendarType::permanent()
        );

        $this->assertEquals($expected, Offer::fromJsonLd($jsonLd));
    }
}
