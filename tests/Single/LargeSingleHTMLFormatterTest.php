<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\HtmlFixture;
use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class LargeSingleHTMLFormatterTest extends TestCase
{
    use HtmlFixture;

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

        $this->assertEquals(
            $this->expectedHtml('singleDateLargeOneDay'),
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

        $this->assertEquals(
            $this->expectedHtml('singleDateLargeWithLeadingZeroOneDay'),
            $this->formatter->format($event)
        );
    }

    public function testFormatHtmlSingleFrench(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2021-08-12T16:00+01:00'),
            new DateTimeImmutable('2021-08-12T21:00:00+01:00')
        );

        $this->assertEquals(
            $this->expectedHtml('singleFrench'),
            (new LargeSingleHTMLFormatter(new Translator('fr')))->format($event)
        );
    }

    public function testFormatHtmlSingleMultipleDaysFrench(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2021-08-12T16:00+01:00'),
            new DateTimeImmutable('2021-08-14T21:00:00+01:00')
        );

        $this->assertEquals(
            $this->expectedHtml('singleMultipleDaysFrench'),
            (new LargeSingleHTMLFormatter(new Translator('fr')))->format($event)
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

        $this->assertEquals(
            $this->expectedHtml('singleDateLargeMoreDays'),
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

        $this->assertEquals(
            $this->expectedHtml('singleDateLargeWithLeadingZerosMoreDays'),
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

        $this->assertEquals(
            $this->expectedHtml('singleDateLargeWholeDay'),
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

        $this->assertEquals(
            $this->expectedHtml('singleDateLargeWholeDayWithStatusUnavailable'),
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

        $this->assertEquals(
            $this->expectedHtml('singleDateLargeWholeDayWithStatusUnavailableAndBookingUnavailable'),
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

        $this->assertEquals(
            $this->expectedHtml('singleDateLargeWholeDayWithStatusAvailableAndBookingUnavailable'),
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

        $this->assertEquals(
            $this->expectedHtml('singleDateSameTime'),
            $this->formatter->format($event)
        );
    }
}
