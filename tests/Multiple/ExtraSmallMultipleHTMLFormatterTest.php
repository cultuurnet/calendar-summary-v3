<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ExtraSmallMultipleHTMLFormatterTest extends TestCase
{
    /**
     * @var ExtraSmallMultipleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new ExtraSmallMultipleHTMLFormatter(new Translator('nl_NL'));
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
            '<span class="cf-date">25</span> <span class="cf-month">nov</span> <span class="cf-year">2025</span>'
            . ' - '
            . '<span class="cf-date">30</span> <span class="cf-month">nov</span> <span class="cf-year">2030</span>',
            $this->formatter->format($offer)
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
            '<span class="cf-date">4</span> <span class="cf-month">mrt</span> <span class="cf-year">2025</span>'
            . ' - '
            . '<span class="cf-date">8</span> <span class="cf-month">mrt</span> <span class="cf-year">2030</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleWithoutLeadingZeroesWithUnavailableStatus(): void
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
            '<span class="cf-date">25</span> <span class="cf-month">nov</span> <span class="cf-year">2025</span>'
            . ' - '
            . '<span class="cf-date">30</span> <span class="cf-month">nov</span> <span class="cf-year">2030</span>'
            . ' <span class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleWithoutLeadingZeroesWithUnavailableStatusAndUnavailableBooking(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Unavailable'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            '<span class="cf-date">25</span> <span class="cf-month">nov</span> <span class="cf-year">2025</span>'
            . ' - '
            . '<span class="cf-date">30</span> <span class="cf-month">nov</span> <span class="cf-year">2030</span>'
            . ' <span class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleWithoutLeadingZeroesWithAvailableStatusAndUnavailableBooking(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Unavailable'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::multiple()
        );

        $this->assertEquals(
            '<span class="cf-date">25</span> <span class="cf-month">nov</span> <span class="cf-year">2025</span>'
            . ' - '
            . '<span class="cf-date">30</span> <span class="cf-month">nov</span> <span class="cf-year">2030</span>'
            . ' <span class="cf-status">(Volzet of uitverkocht)</span>',
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
            '<span class="cf-date">4</span> <span class="cf-month">okt</span> <span class="cf-year">2025</span>' .
            ' - <span class="cf-date">8</span> <span class="cf-month">okt</span> <span class="cf-year">2030</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAMultipleWithSameBeginAndEndDay(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('08-10-2025 12:00'),
            new DateTimeImmutable('08-10-2025 14:00'),
            CalendarType::multiple()
        );

        $output = '<span class="cf-date">8</span> <span class="cf-month">okt</span> <span class="cf-year">2025</span>';

        $this->assertEquals(
            $output,
            $this->formatter->format($offer)
        );
    }
}
