<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use Carbon\CarbonImmutable;
use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
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
        CarbonImmutable::setTestNow(CarbonImmutable::create(2021, 5, 3));
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
            CarbonImmutable::create(2021, 5, 3)->setTime(11, 30),
            CarbonImmutable::create(2021, 5, 3)->setTime(20, 30),
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
            CarbonImmutable::create(2021, 5, 3)->setTime(18, 0),
            CarbonImmutable::create(2021, 5, 3)->setTime(21, 30),
            CalendarType::multiple()
        );

        $this->assertEquals(
            'Vanavond',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleSingleDateSmTomorrow(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            CarbonImmutable::create(2021, 5, 4)->setTime(18, 0),
            CarbonImmutable::create(2021, 5, 4)->setTime(21, 30),
            CalendarType::multiple()
        );

        $this->assertEquals(
            'Morgen',
            $this->formatter->format($event)
        );
    }

    public function testFormatMultipleCurrentWeek(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            CarbonImmutable::create(2021, 5, 9)->setTime(18, 0),
            CarbonImmutable::create(2021, 5, 9)->setTime(21, 30),
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

    public function testFormatMultipleCurrentYear(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('04-03-2021'),
            new DateTimeImmutable('08-03-2021'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            '4 mrt - 8 mrt',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleStartsCurrentYear(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('04-03-2021'),
            new DateTimeImmutable('08-03-2030'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            '4 mrt - 8 mrt 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleEndsCurrentYear(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('04-03-2020'),
            new DateTimeImmutable('08-03-2021'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            '4 mrt 2020 - 8 mrt',
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
