<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ExtraSmallSinglePlainTextFormatterTest extends TestCase
{
    /**
     * @var SmallSinglePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new ExtraSmallSinglePlainTextFormatter(new Translator('nl_NL'));
    }

    public function testFormatPlainTextSingleDateXsOneDay(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            '25 jan 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsWithLeadingZeroOneDay(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-08T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-08T21:30:00+01:00')
        );

        $this->assertEquals(
            '8 jan 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsMoreDays(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-28T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 25 jan 2018 tot 28 jan 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsWithLeadingZeroMoreDays(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-06T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-08T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 6 jan 2018 tot 8 jan 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsOneDayWithStatusUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            '25 jan 2018 (geannuleerd)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsOneDayWithStatusTemporarilyUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('TemporarilyUnavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            '25 jan 2018 (uitgesteld)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsMoreDaysWithStatusUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-28T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 25 jan 2018 tot 28 jan 2018 (geannuleerd)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsMoreDaysWithStatusUnavailableAndBookingUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Unavailable'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-28T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 25 jan 2018 tot 28 jan 2018 (geannuleerd)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsMoreDaysWithStatusAvailableAndBookingUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Unavailable'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-28T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 25 jan 2018 tot 28 jan 2018 (Volzet of uitverkocht)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsMoreDaysWithStatusTemporarilyUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('TemporarilyUnavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-28T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 25 jan 2018 tot 28 jan 2018 (uitgesteld)',
            $this->formatter->format($event)
        );
    }
}
