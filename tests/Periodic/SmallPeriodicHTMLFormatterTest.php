<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

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
    }

    public function testFormatAPeriodWithoutLeadingZeroes(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
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
            new DateTimeImmutable('04-03-2025'),
            new DateTimeImmutable('08-03-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
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

    public function testFormatAPeriodWithoutLeadingZeroesWithUnavailableStatus(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
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
            new DateTimeImmutable('25-03-2025'),
            new DateTimeImmutable('30-03-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
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
            new DateTimeImmutable('04-10-2025'),
            new DateTimeImmutable('08-10-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="from meta">Vanaf</span>' .
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
            new DateTimeImmutable('12-03-2015'),
            new DateTimeImmutable('18-03-2030'),
            CalendarType::periodic()
        );

        $expected =
            '<span class="to meta">Tot</span>' .
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
