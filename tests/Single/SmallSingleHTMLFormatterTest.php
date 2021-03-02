<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
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
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-date">25</span> <span class="cf-month">jan</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsOneDayWithStatusUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-date">25</span> <span class="cf-month">jan</span> <span class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsOneDayWithStatusUnavailableAndReason(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', ['nl' => 'Covid-19']),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-date">25</span> <span class="cf-month">jan</span> <span title="Covid-19" class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsWithLeadingZeroOneDay(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new DateTimeImmutable('2018-01-08T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-08T21:30:00+01:00')
        );

        $this->assertEquals(
            '<span class="cf-date">8</span> <span class="cf-month">jan</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsMoreDays(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-27T21:30:00+01:00')
        );

        $expectedOutput = '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">25</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">27</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsMoreDaysWithUnavailableStatus(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-27T21:30:00+01:00')
        );

        $expectedOutput = '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">25</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">27</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
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
            new DateTimeImmutable('2018-01-06T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-08T21:30:00+01:00')
        );

        $expectedOutput = '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">8</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
