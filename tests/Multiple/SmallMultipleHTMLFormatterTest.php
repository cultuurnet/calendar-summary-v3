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

final class SmallMultipleHTMLFormatterTest extends TestCase
{
    /**
     * @var SmallMultipleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new SmallMultipleHTMLFormatter(new Translator('nl_NL'));
        CarbonImmutable::setTestNow(CarbonImmutable::create(2021, 5, 3));
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
            '<span class="cf-days">Vandaag</span>',
            $this->formatter->format($offer)
        );
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
            '<span class="cf-date">25 nov 2025</span> '
            . '<span class="cf-to cf-meta">-</span> <span class="cf-date">30 nov 2030</span>',
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
            '<span class="cf-date">25 nov 2025</span> '
            . '<span class="cf-to cf-meta">-</span> <span class="cf-date">30 nov 2030</span>'
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
            '<span class="cf-date">25 nov 2025</span> '
            . '<span class="cf-to cf-meta">-</span> <span class="cf-date">30 nov 2030</span>'
            . ' <span class="cf-status">(Volzet of uitverkocht)</span>',
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
            '<span class="cf-date">4 mrt 2025</span> '
            . '<span class="cf-to cf-meta">-</span> <span class="cf-date">8 mrt 2030</span>',
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
            '<span class="cf-date">4 okt 2025</span> '
            . '<span class="cf-to cf-meta">-</span> <span class="cf-date">8 okt 2030</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAMultipleWithSameBeginAndEndDate(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('08-10-2025'),
            new DateTimeImmutable('08-10-2025'),
            CalendarType::multiple()
        );

        $output = '<span class="cf-weekday cf-meta">Woensdag</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">8 okt 2025</span>';

        $this->assertEquals(
            $output,
            $this->formatter->format($offer)
        );
    }
}
