<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class SmallSingleHTMLFormatterTest extends TestCase
{
    /**
     * @var SmallSingleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new SmallSingleHTMLFormatter(new Translator('nl_NL'));
    }

    public function testFormatHTMLSingleDateXsOneDay(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-date">25</span> <span class="cf-month">jan</span> <span class="cf-year">2018</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHtmlSingleDateSmOneDayCurrentYear(): void
    {
        $someDayInCurrentYear =
            (new DateTimeImmutable())->add(new DateInterval('P10D'))->format('Y') === (new DateTimeImmutable())->format('Y')
                ? (new DateTimeImmutable())->add(new DateInterval('P10D')) : (new DateTimeImmutable())->sub(new DateInterval('P10D'));
        // This is to catch edge cases when running the tests at the end of or beginning of the current year
        $expected = '<span class="cf-date">' .
            $someDayInCurrentYear->format('d') .
            '</span> <span class="cf-month">' .
            strtolower($someDayInCurrentYear->format('M')) .
            '</span>';
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable($someDayInCurrentYear->format('Y-m-d') . 'T20:00:00+01:00'),
            new DateTimeImmutable($someDayInCurrentYear->format('Y-m-d') . 'T21:30:00+01:00')
        );

        $this->assertEquals(
            $expected,
            $this->formatter->format($event)
        );
    }


    public function testFormatHTMLSingleDateXsToday(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable((new DateTimeImmutable())->format('Y-m-d') . 'T11:00:00+01:00'),
            new DateTimeImmutable((new DateTimeImmutable())->format('Y-m-d') . 'T20:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-days">Vandaag</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsTonight(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable((new DateTimeImmutable())->format('Y-m-d') . 'T18:00:00+01:00'),
            new DateTimeImmutable((new DateTimeImmutable())->format('Y-m-d') . 'T20:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-days">Vanavond</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsTomorrow(): void
    {
        $tomorrow = (new DateTimeImmutable())->add(new DateInterval('P1D'));
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable($tomorrow->format('Y-m-d') . 'T18:30:00+01:00'),
            new DateTimeImmutable($tomorrow->format('Y-m-d') . 'T22:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-days">Morgen</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleCurrentWeek(): void
    {
        $offSet = (int) (new DateTimeImmutable())->format('w');
        $daysTillSunday = 7 - $offSet;
        $thisSunday = (new DateTimeImmutable())->add(new DateInterval('P' . $daysTillSunday . 'D'));
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable($thisSunday->format('Y-m-d') . 'T18:30:00+01:00'),
            new DateTimeImmutable($thisSunday->format('Y-m-d') . 'T22:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-meta">Deze</span> <span class="cf-days">zondag</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsOneDayWithStatusUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-date">25</span> <span class="cf-month">jan</span> <span class="cf-year">2018</span> <span class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsOneDayWithStatusUnavailableAndReason(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', ['nl' => 'Covid-19']),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-date">25</span> <span class="cf-month">jan</span> <span class="cf-year">2018</span> <span title="Covid-19" class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsWithLeadingZeroOneDay(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-08T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-08T21:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-date">8</span> <span class="cf-month">jan</span> <span class="cf-year">2018</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsMoreDays(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-27T21:30:00+01:00')
        );

        $expectedOutput = '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">25</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-year">2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">27</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-year">2018</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsMoreDaysWithUnavailableStatus(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-27T21:30:00+01:00')
        );

        $expectedOutput = '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">25</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-year">2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">27</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-year">2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-status">(geannuleerd)</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsWithLeadingZeroMoreDays(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-06T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-08T21:30:00+01:00')
        );

        $expectedOutput = '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-year">2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">8</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-year">2018</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
