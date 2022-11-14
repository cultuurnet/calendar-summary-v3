<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class SmallMultiplePlainTextFormatterTest extends TestCase
{
    /**
     * @var SmallMultiplePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new SmallMultiplePlainTextFormatter(new Translator('nl_NL'));
    }

    public function testFormatMultipleWithoutLeadingZeroes(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            '25 nov 2025 - 30 nov 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleOnSamedayToday(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable((new DateTimeImmutable())->format('Y-m-d') . 'T11:00:00+01:00'),
            new DateTimeImmutable((new DateTimeImmutable())->format('Y-m-d') . 'T15:00:00+01:00'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            'Vandaag',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleOnSamedayTonight(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable((new DateTimeImmutable())->format('Y-m-d') . 'T18:00:00+01:00'),
            new DateTimeImmutable((new DateTimeImmutable())->format('Y-m-d') . 'T21:00:00+01:00'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            'Vanavond',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleSingleDateSmTomorrow(): void
    {
        $tomorrow = (new DateTimeImmutable())->add(new DateInterval('P1D'));
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable($tomorrow->format('Y-m-d') . 'T18:30:00+01:00'),
            new DateTimeImmutable($tomorrow->format('Y-m-d') . 'T22:30:00+01:00'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            'Morgen',
            $this->formatter->format($event)
        );
    }

    public function testFormatMultipleCurrentWeek(): void
    {
        $offSet = (int) (new DateTimeImmutable())->format('w');
        $daysTillSunday = 7 - $offSet;
        $thisSunday = (new DateTimeImmutable())->add(new DateInterval('P' . $daysTillSunday . 'D'));
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable($thisSunday->format('Y-m-d') . 'T18:30:00+01:00'),
            new DateTimeImmutable($thisSunday->format('Y-m-d') . 'T22:30:00+01:00'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            'Deze zondag',
            $this->formatter->format($event)
        );
    }

    public function testFormatMultipleWithLeadingZeroes(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('04-03-2025'),
            new DateTimeImmutable('08-03-2030'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            '4 mrt 2025 - 8 mrt 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleWithUnavailableStatus(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            '25 nov 2025 - 30 nov 2030 (geannuleerd)',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleDayWithoutLeadingZero(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-03-2025'),
            new DateTimeImmutable('30-03-2030'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            '25 mrt 2025 - 30 mrt 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleMonthWithoutLeadingZero(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('04-10-2025'),
            new DateTimeImmutable('08-10-2030'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            '4 okt 2025 - 8 okt 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleDayWithUnavailableStatus(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-03-2025'),
            new DateTimeImmutable('30-03-2030'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            '25 mrt 2025 - 30 mrt 2030 (geannuleerd)',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithSameBeginAndEndDate(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('08-10-2025'),
            new DateTimeImmutable('08-10-2025'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            'Wo 8 okt 2025',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithSameBeginAndEndDateWithUnavailableStatus(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('08-10-2025'),
            new DateTimeImmutable('08-10-2025'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            'Wo 8 okt 2025 (geannuleerd)',
            $this->formatter->format($offer)
        );
    }
}
