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

final class MediumMultipleHTMLFormatterTest extends TestCase
{
    /**
     * @var MediumMultipleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MediumMultipleHTMLFormatter(new Translator('nl_NL'), false);
    }

    public function testFormatHTMLMultipleDateMediumOneDay(): void
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

        $expectedOutput = '<ul class="cnw-event-date-info"><li>';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Do</span> ';
        $expectedOutput .= '<span class="cf-date">9 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-weekday cf-meta">Do</span> ';
        $expectedOutput .= '<span class="cf-date">16 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-weekday cf-meta">Do</span> ';
        $expectedOutput .= '<span class="cf-date">23 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-weekday cf-meta">Do</span> ';
        $expectedOutput .= '<span class="cf-date">30 november 2017</span></li></ul>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLMultipleDateMediumOneDayWithUnavailableStatus(): void
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

        $expectedOutput = '<ul class="cnw-event-date-info"><li>';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Do</span> ';
        $expectedOutput .= '<span class="cf-date">9 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-weekday cf-meta">Do</span> ';
        $expectedOutput .= '<span class="cf-date">16 november 2017</span> <span class="cf-status">(geannuleerd)</span></li>';
        $expectedOutput .= '<li><span class="cf-weekday cf-meta">Do</span> ';
        $expectedOutput .= '<span class="cf-date">23 november 2017</span> <span class="cf-status">(Volzet of uitverkocht)</span></li>';
        $expectedOutput .= '<li><span class="cf-weekday cf-meta">Do</span> ';
        $expectedOutput .= '<span class="cf-date">30 november 2017</span></li></ul>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLMultipleDateMediumMoreDays(): void
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

        $expectedOutput = '<ul class="cnw-event-date-info"><li>';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">ma</span> ';
        $expectedOutput .= '<span class="cf-date">6 november 2017</span> ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">do</span> ';
        $expectedOutput .= '<span class="cf-date">9 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-from cf-meta">Van</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">di</span> ';
        $expectedOutput .= '<span class="cf-date">14 november 2017</span> ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">do</span> ';
        $expectedOutput .= '<span class="cf-date">16 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-from cf-meta">Van</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">di</span> ';
        $expectedOutput .= '<span class="cf-date">21 november 2017</span> ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">do</span> ';
        $expectedOutput .= '<span class="cf-date">23 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-from cf-meta">Van</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">di</span> ';
        $expectedOutput .= '<span class="cf-date">28 november 2017</span> ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">do</span> ';
        $expectedOutput .= '<span class="cf-date">30 november 2017</span></li></ul>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testItWillShowEventHasConcludedWhenAllPastDatesAreHidden(): void
    {
        $formatter = new MediumMultipleHTMLFormatter(new Translator('nl_NL'), true);
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
