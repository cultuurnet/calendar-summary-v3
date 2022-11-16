<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use Carbon\CarbonImmutable;
use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class SmallSinglePlainTextFormatterTest extends TestCase
{
    /**
     * @var SmallSinglePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new SmallSinglePlainTextFormatter(new Translator('nl_NL'));
    }

    public function testFormatPlainTextSingleDateSmOneDay(): void
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

    public function testFormatPlainTextSingleDateSmOneDayCurrentYear(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::create(2021, 3, 4));
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            CarbonImmutable::create(2021, 5, 4),
            CarbonImmutable::create(2021, 5, 4)
        );

        $this->assertEquals(
            '4 mei',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateSmToday(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::create(2021, 5, 4));
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            CarbonImmutable::create(2021, 5, 4)->setTime(11, 30),
            CarbonImmutable::create(2021, 5, 4)->setTime(20, 30)
        );

        $this->assertEquals(
            'Vandaag',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateSmTonight(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::create(2021, 5, 4));
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            CarbonImmutable::create(2021, 5, 4)->setTime(18, 30),
            CarbonImmutable::create(2021, 5, 4)->setTime(21, 30)
        );

        $this->assertEquals(
            'Vanavond',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateSmTomorrow(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::create(2021, 5, 4));
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            CarbonImmutable::create(2021, 5, 5)->setTime(18, 30),
            CarbonImmutable::create(2021, 5, 5)->setTime(21, 30)
        );

        $this->assertEquals(
            'Morgen',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleCurrentWeek(): void
    {
        CarbonImmutable::setTestNow(CarbonImmutable::create(2021, 5, 3));
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            CarbonImmutable::create(2021, 5, 9)->setTime(18, 30),
            CarbonImmutable::create(2021, 5, 9)->setTime(18, 30)
        );

        $this->assertEquals(
            'Deze zondag',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateSmWithLeadingZeroOneDay(): void
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

    public function testFormatPlainTextSingleDateSmMoreDays(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-28T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 25 jan tot 28 jan',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateSmWithLeadingZeroMoreDays(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-06T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-08T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 6 jan tot 8 jan',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateSmOneDayWithStatusUnavailable(): void
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

    public function testFormatPlainTextSingleDateSmOneDayWithStatusTemporarilyUnavailable(): void
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

    public function testFormatPlainTextSingleDateSmMoreDaysWithStatusUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-28T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 25 jan tot 28 jan (geannuleerd)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateSmMoreDaysWithStatusUnavailableAndBookingUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Unavailable'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-28T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 25 jan tot 28 jan (geannuleerd)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateSmMoreDaysWithStatusAvailableAndBookingUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Unavailable'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-28T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 25 jan tot 28 jan (Volzet of uitverkocht)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateSmMoreDaysWithStatusTemporarilyUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('TemporarilyUnavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-28T21:30:00+01:00')
        );

        $this->assertEquals(
            'Van 25 jan tot 28 jan (uitgesteld)',
            $this->formatter->format($event)
        );
    }
}
