<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class MediumSinglePlainTextFormatterTest extends TestCase
{
    /**
     * @var MediumSinglePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MediumSinglePlainTextFormatter(new Translator('nl_NL'));
    }

    public function testFormatPlainTextSingleDateMediumOneDay(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            'Donderdag 25 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateMediumWithLeadingZeroOneDay(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new DateTimeImmutable('2018-01-08T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-08T21:30:00+01:00')
        );

        $this->assertEquals(
            'Maandag 8 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateMediumMoreDays(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-27T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van donderdag 25 januari 2018 tot zaterdag 27 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateMediumWithLeadingZeroMoreDays(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new DateTimeImmutable('2018-01-06T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-08T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van zaterdag 6 januari 2018 tot maandag 8 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateMediumOneDayWithUnavailableStatus(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            'Donderdag 25 januari 2018 (geannuleerd)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateMediumOneDayWithTemporarilyUnavailableStatus(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            'Donderdag 25 januari 2018 (uitgesteld)',
            $this->formatter->format($event)
        );
    }
}
