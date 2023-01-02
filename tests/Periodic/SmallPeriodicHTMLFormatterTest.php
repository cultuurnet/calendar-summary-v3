<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use Carbon\CarbonImmutable;
use CultuurNet\CalendarSummaryV3\CalendarSummaryTester;
use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class SmallPeriodicHTMLFormatterTest extends TestCase
{
    /**
     * @var SmallPeriodicHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new SmallPeriodicHTMLFormatter(new Translator('nl_NL'));
        CalendarSummaryTester::setTestNow(CarbonImmutable::create(2021, 5, 3));
    }

    public function testFormatAPeriodWithoutLeadingZeroes(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
            ' ' .
            '<span class="cf-days">di</span>' .
            ' ' .
            '<span class="cf-date">25</span>' .
            ' ' .
            '<span class="cf-month">nov</span>' .
            ' ' .
            '<span class="cf-year">2025</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithLeadingZeroes(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('04-03-2025'),
            new DateTimeImmutable('08-03-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
            ' ' .
            '<span class="cf-days">di</span>' .
            ' ' .
            '<span class="cf-date">4</span>' .
            ' ' .
            '<span class="cf-month">mrt</span>' .
            ' ' .
            '<span class="cf-year">2025</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodCurrentYear(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2021'),
            new DateTimeImmutable('30-11-2021'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
            ' ' .
            '<span class="cf-days">do</span>' .
            ' ' .
            '<span class="cf-date">25</span>' .
            ' ' .
            '<span class="cf-month">nov</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodStartsCurrentYear(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2021'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
            ' ' .
            '<span class="cf-days">do</span>' .
            ' ' .
            '<span class="cf-date">25</span>' .
            ' ' .
            '<span class="cf-month">nov</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodEndsCurrentYear(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('04-03-2020'),
            new DateTimeImmutable('08-03-2021'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="to meta">Tot</span>' .
            ' ' .
            '<span class="cf-days">ma</span>' .
            ' ' .
            '<span class="cf-date">8</span>' .
            ' ' .
            '<span class="cf-month">mrt</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithoutLeadingZeroesWithUnavailableStatus(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
            ' ' .
            '<span class="cf-days">di</span>' .
            ' ' .
            '<span class="cf-date">25</span>' .
            ' ' .
            '<span class="cf-month">nov</span>' .
            ' ' .
            '<span class="cf-year">2025</span>' .
            ' ' .
            '<span class="cf-status">(geannuleerd)</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodDayWithoutLeadingZero(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-03-2025'),
            new DateTimeImmutable('30-03-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
            ' ' .
            '<span class="cf-days">di</span>' .
            ' ' .
            '<span class="cf-date">25</span>' .
            ' ' .
            '<span class="cf-month">mrt</span>' .
            ' ' .
            '<span class="cf-year">2025</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodMonthWithoutLeadingZero(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('04-10-2025'),
            new DateTimeImmutable('08-10-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
            ' ' .
            '<span class="cf-days">za</span>' .
            ' ' .
            '<span class="cf-date">4</span>' .
            ' ' .
            '<span class="cf-month">okt</span>' .
            ' ' .
            '<span class="cf-year">2025</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodThatHasAlreadyStarted(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('12-03-2015'),
            new DateTimeImmutable('18-03-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="to meta">Tot</span>' .
            ' ' .
            '<span class="cf-days">ma</span>' .
            ' ' .
            '<span class="cf-date">18</span>' .
            ' ' .
            '<span class="cf-month">mrt</span>' .
            ' ' .
            '<span class="cf-year">2030</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }
}
