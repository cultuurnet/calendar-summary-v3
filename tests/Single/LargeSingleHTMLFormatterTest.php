<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class LargeSingleHTMLFormatterTest extends TestCase
{
    /**
     * @var LargeSingleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        date_default_timezone_set('Europe/Brussels');
        $this->formatter = new LargeSingleHTMLFormatter(new Translator('nl_NL'));
    }

    public function testFormatHTMLSingleDateLargeOneDay(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00')
        );

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-25T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">25 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2018-01-25T21:30:00+01:00">';
        $expectedOutput .= '<span class="cf-time">21:30</span>';
        $expectedOutput .= '</time>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateLargeWithLeadingZeroOneDay(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-08T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-08T21:30:00+01:00')
        );

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-08T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Maandag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">8 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2018-01-08T21:30:00+01:00">';
        $expectedOutput .= '<span class="cf-time">21:30</span>';
        $expectedOutput .= '</time>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateLargeMoreDays(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-28T21:30:00+01:00')
        );

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-25T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">25 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2018-01-28T21:30:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">zondag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">28 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">21:30</span>';
        $expectedOutput .= '</time>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateLargeWithLeadingZerosMoreDays(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-06T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-08T21:30:00+01:00')
        );

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-06T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">zaterdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2018-01-08T21:30:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">maandag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">8 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">21:30</span>';
        $expectedOutput .= '</time>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateLargeWholeDay(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-06T00:00:00+01:00'),
            new DateTimeImmutable('2018-01-06T23:59:59+01:00')
        );

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-06T00:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Zaterdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6 januari 2018</span>';
        $expectedOutput .= '</time>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateLargeWholeDayWithStatusUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-06T00:00:00+01:00'),
            new DateTimeImmutable('2018-01-06T23:59:59+01:00')
        );

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-06T00:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Zaterdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6 januari 2018</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-status">(geannuleerd)</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateLargeWholeDayWithStatusUnavailableAndBookingUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Unavailable'),
            new DateTimeImmutable('2018-01-06T00:00:00+01:00'),
            new DateTimeImmutable('2018-01-06T23:59:59+01:00')
        );

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-06T00:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Zaterdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6 januari 2018</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-status">(geannuleerd)</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateLargeWholeDayWithStatusAvailableAndBookingUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Unavailable'),
            new DateTimeImmutable('2018-01-06T00:00:00+01:00'),
            new DateTimeImmutable('2018-01-06T23:59:59+01:00')
        );

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-06T00:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Zaterdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6 januari 2018</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-status">(Volzet of uitverkocht)</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateSameTime(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-06T13:30:00+01:00'),
            new DateTimeImmutable('2018-01-06T13:30:00+01:00')
        );

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-06T13:30:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Zaterdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">13:30</span>';
        $expectedOutput .= '</time>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
