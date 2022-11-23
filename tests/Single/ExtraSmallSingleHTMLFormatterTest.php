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

final class ExtraSmallSingleHTMLFormatterTest extends TestCase
{
    /**
     * @var SmallSingleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new ExtraSmallSingleHTMLFormatter(new Translator('nl_NL'));
        CarbonImmutable::setTestNow(CarbonImmutable::create(2021, 5, 9));
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
            '<span class="cf-date">25</span> ' .
            '<span class="cf-month">jan</span> ' .
            '<span class="cf-year">2018</span>',
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
            '<span class="cf-date">25</span> ' .
            '<span class="cf-month">jan</span> ' .
            '<span class="cf-year">2018</span> ' .
            '<span class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHtmlSingleDateCurrentYEar(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            CarbonImmutable::create(2021, 1, 8)->setTime(1, 0),
            CarbonImmutable::create(2021, 1, 8)->setTime(21, 30)
        );

        $this->assertEquals(
            '<span class="cf-date">8</span> <span class="cf-month">jan</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHtmlSingleDateXsMoreDaysCurrentYear(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            CarbonImmutable::create(2021, 1, 23)->setTime(1, 0),
            CarbonImmutable::create(2021, 1, 28)->setTime(21, 30)
        );

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> ' .
            '<span class="cf-date">23</span> ' .
            '<span class="cf-month">jan</span> ' .
            '<span class="cf-to cf-meta">tot</span> ' .
            '<span class="cf-date">28</span> ' .
            '<span class="cf-month">jan</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHtmlSingleDateXsMoreDaysStartCurrentYear(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            CarbonImmutable::create(2021, 12, 23)->setTime(1, 0),
            CarbonImmutable::create(2022, 1, 28)->setTime(21, 30)
        );

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> ' .
            '<span class="cf-date">23</span> ' .
            '<span class="cf-month">dec</span> ' .
            '<span class="cf-to cf-meta">tot</span> ' .
            '<span class="cf-date">28</span> ' .
            '<span class="cf-month">jan</span> ' .
            '<span class="cf-year">2022</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHtmlSingleDateXsMoreDaysEndCurrentYear(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            CarbonImmutable::create(2020, 12, 23)->setTime(1, 0),
            CarbonImmutable::create(2021, 1, 28)->setTime(21, 30)
        );

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> ' .
            '<span class="cf-date">23</span> ' .
            '<span class="cf-month">dec</span> ' .
            '<span class="cf-year">2020</span> ' .
            '<span class="cf-to cf-meta">tot</span> ' .
            '<span class="cf-date">28</span> ' .
            '<span class="cf-month">jan</span>',
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
            '<span class="cf-date">25</span> ' .
            '<span class="cf-month">jan</span> ' .
            '<span class="cf-year">2018</span> ' .
            '<span title="Covid-19" class="cf-status">(geannuleerd)</span>',
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
