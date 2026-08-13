<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\HtmlFixture;
use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class LargeMultipleHTMLFormatterTest extends TestCase
{
    use HtmlFixture;

    /**
     * @var LargeMultipleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        date_default_timezone_set('Europe/Brussels');
        $this->formatter = new LargeMultipleHTMLFormatter(new Translator('nl_NL'), false);
    }

    public function testFormatHTMLMultipleDateLargeOneDay(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEvents.json'), true);

        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::multiple()
        );

        $newEvents = [];
        foreach ($subEvents as $subEvent) {
            $newEvents[] = new Offer(
                OfferType::event(),
                new Status('Available', []),
                new BookingAvailability('Available'),
                new DateTimeImmutable($subEvent['startDate']),
                new DateTimeImmutable($subEvent['endDate'])
            );
        }

        $event = $event->withSubEvents($newEvents);

        $this->assertEquals(
            $this->expectedHtml('multipleDateLargeOneDay'),
            $this->formatter->format($event)
        );
    }

    public function testFormatHtmlMultipleDaysFrench(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEventsMoreDays.json'), true);
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::multiple()
        );
        $newEvents = [];
        foreach ($subEvents as $subEvent) {
            $newEvents[] = new Offer(
                OfferType::event(),
                new Status('Available', []),
                new BookingAvailability('Available'),
                new DateTimeImmutable($subEvent['startDate']),
                new DateTimeImmutable($subEvent['endDate'])
            );
        }
        $event = $event->withSubEvents($newEvents);

        $this->assertEquals(
            $this->expectedHtml('multipleDaysFrench'),
            (new LargeMultipleHTMLFormatter(new Translator('fr'), false))->format($event)
        );
    }

    public function testFormatHTMLMultipleDateLargeOneDayWithUnavailability(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEvents.json'), true);
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::multiple()
        );

        $newEvents = [];
        foreach ($subEvents as $subEvent) {
            $newEvents[] = new Offer(
                OfferType::event(),
                new Status('Available', []),
                new BookingAvailability('Available'),
                new DateTimeImmutable($subEvent['startDate']),
                new DateTimeImmutable($subEvent['endDate'])
            );
        }
        $newEvents[1] = $newEvents[1]->withAvailability(
            new Status('Unavailable', []),
            new BookingAvailability('Unavailable')
        );
        $newEvents[2] = $newEvents[2]->withAvailability(
            new Status('Available', []),
            new BookingAvailability('Unavailable')
        );
        $event = $event->withSubEvents($newEvents);

        $this->assertEquals(
            $this->expectedHtml('multipleDateLargeOneDayWithUnavailability'),
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLMultipleDateLargeMoreDays(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEventsMoreDays.json'), true);
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::multiple()
        );

        $newEvents = [];
        foreach ($subEvents as $subEvent) {
            $newEvents[] = new Offer(
                OfferType::event(),
                new Status('Available', []),
                new BookingAvailability('Available'),
                new DateTimeImmutable($subEvent['startDate']),
                new DateTimeImmutable($subEvent['endDate'])
            );
        }

        $event = $event->withSubEvents($newEvents);

        $this->assertEquals(
            $this->expectedHtml('multipleDateLargeMoreDays'),
            $this->formatter->format($event)
        );
    }

    public function testItWillShowEventHasConcludedWhenAllPastDatesAreHidden(): void
    {
        $formatter = new LargeMultipleHTMLFormatter(new Translator('nl_NL'), true);
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEvents.json'), true);
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::multiple()
        );

        $newEvents = [];
        foreach ($subEvents as $subEvent) {
            $newEvents[] = new Offer(
                OfferType::event(),
                new Status('Available', []),
                new BookingAvailability('Available'),
                new DateTimeImmutable($subEvent['startDate']),
                new DateTimeImmutable($subEvent['endDate'])
            );
        }

        $event = $event->withSubEvents($newEvents);
        $this->assertEquals(
            '<span>Evenement afgelopen</span>',
            $formatter->format($event)
        );
    }
}
