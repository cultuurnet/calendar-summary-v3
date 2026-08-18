<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\HtmlFixture;
use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Childcare;
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
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/sub-events.json'), true);

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
            $this->expectedHtml('multiple-date-large-one-day'),
            $this->formatter->format($event)
        );
    }

    public function testFormatHtmlMultipleDaysFrench(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/sub-events-more-days.json'), true);
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
            $this->expectedHtml('multiple-days-french'),
            (new LargeMultipleHTMLFormatter(new Translator('fr'), false))->format($event)
        );
    }

    public function testFormatHTMLMultipleDateLargeOneDayWithUnavailability(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/sub-events.json'), true);
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
            $this->expectedHtml('multiple-date-large-one-day-with-unavailability'),
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLMultipleDateLargeMoreDays(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/sub-events-more-days.json'), true);
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
            $this->expectedHtml('multiple-date-large-more-days'),
            $this->formatter->format($event)
        );
    }

    public function testItWillShowEventHasConcludedWhenAllPastDatesAreHidden(): void
    {
        $formatter = new LargeMultipleHTMLFormatter(new Translator('nl_NL'), true);
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/sub-events.json'), true);
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

    /**
     * Every sub-event has a list item of its own, so its childcare is nested inside of it.
     */
    public function testItNestsTheChildcareInsideTheSubEventItBelongsTo(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/sub-events-with-childcare.json'), true);
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
            $newEvents[] = (new Offer(
                OfferType::event(),
                new Status('Available', []),
                new BookingAvailability('Available'),
                new DateTimeImmutable($subEvent['startDate']),
                new DateTimeImmutable($subEvent['endDate'])
            ))
                ->withChildcare(Childcare::fromArrayOrNull($subEvent['childcare'] ?? null))
                ->withOvernight($subEvent['overnight'] ?? false);
        }

        $event = $event->withSubEvents($newEvents);

        $this->assertEquals(
            $this->expectedHtml('multiple-dates-with-childcare-and-overnight'),
            $this->formatter->format($event)
        );
    }

    /**
     * Reporting that a date has no childcare only makes sense next to a date that has one,
     * so an offer that never has any childcare keeps quiet about it.
     */
    public function testItOnlyReportsAnAbsentChildcareNextToAPresentOne(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::multiple()
        );

        $withoutAnyChildcare = $event->withSubEvents([$this->subEvent(), $this->subEvent()]);

        $withOneChildcare = $event->withSubEvents(
            [
                $this->subEvent()->withChildcare(new Childcare('07:00', '18:00')),
                $this->subEvent(),
            ]
        );

        $this->assertStringNotContainsString(
            'Geen opvang',
            $this->formatter->format($withoutAnyChildcare)
        );
        $this->assertStringContainsString(
            '<li class="cf-childcare">(Geen opvang)</li>',
            $this->formatter->format($withOneChildcare)
        );
    }

    private function subEvent(): Offer
    {
        return new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2026-08-13T08:00:00+02:00'),
            new DateTimeImmutable('2026-08-13T17:00:00+02:00')
        );
    }
}
