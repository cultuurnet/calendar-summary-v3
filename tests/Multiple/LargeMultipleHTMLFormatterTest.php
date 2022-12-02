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

final class LargeMultipleHTMLFormatterTest extends TestCase
{
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

        $expectedOutput = '<ul class="cnw-event-date-info"><li>';
        $expectedOutput .= '<time itemprop="startDate" datetime="2017-11-09T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">9 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-09T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';
        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-16T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">16 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-16T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';
        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-23T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">23 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-23T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';
        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-30T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">30 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-30T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li></ul>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextMultipleDaysFrench(): void
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

        $expectedOutput = '<ul class="cnw-event-date-info"><li><time itemprop="startDate" datetime="2017-11-06T20:00:00+01:00">';
        $expectedOutput .='<span class="cf-from cf-meta">Du</span> <span class="cf-weekday cf-meta">lundi</span> ';
        $expectedOutput .='<span class="cf-date">6 novembre 2017</span> <span class="cf-at cf-meta">à</span> ';
        $expectedOutput .='<span class="cf-time">20:00</span></time> ';
        $expectedOutput .='<span class="cf-to cf-meta">au</span> <time itemprop="endDate" datetime="2017-11-09T22:00:00+01:00">';
        $expectedOutput .='<span class="cf-weekday cf-meta">jeudi</span> <span class="cf-date">9 novembre 2017</span> ';
        $expectedOutput .='<span class="cf-at cf-meta">à</span> <span class="cf-time">22:00</span></time></li>';
        $expectedOutput .='<li><time itemprop="startDate" datetime="2017-11-14T20:00:00+01:00"><span class="cf-from cf-meta">Du</span> ';
        $expectedOutput .='<span class="cf-weekday cf-meta">mardi</span> <span class="cf-date">14 novembre 2017</span> ';
        $expectedOutput .='<span class="cf-at cf-meta">à</span> <span class="cf-time">20:00</span></time> ';
        $expectedOutput .='<span class="cf-to cf-meta">au</span> <time itemprop="endDate" datetime="2017-11-16T22:00:00+01:00">';
        $expectedOutput .='<span class="cf-weekday cf-meta">jeudi</span> <span class="cf-date">16 novembre 2017</span> ';
        $expectedOutput .='<span class="cf-at cf-meta">à</span> <span class="cf-time">22:00</span>';
        $expectedOutput .='</time></li><li><time itemprop="startDate" datetime="2017-11-21T20:00:00+01:00">';
        $expectedOutput .='<span class="cf-from cf-meta">Du</span> <span class="cf-weekday cf-meta">mardi</span> ';
        $expectedOutput .='<span class="cf-date">21 novembre 2017</span> <span class="cf-at cf-meta">à</span> ';
        $expectedOutput .='<span class="cf-time">20:00</span></time> <span class="cf-to cf-meta">au</span> ';
        $expectedOutput .='<time itemprop="endDate" datetime="2017-11-23T22:00:00+01:00">';
        $expectedOutput .='<span class="cf-weekday cf-meta">jeudi</span> <span class="cf-date">23 novembre 2017</span> ';
        $expectedOutput .='<span class="cf-at cf-meta">à</span> <span class="cf-time">22:00</span></time></li>';
        $expectedOutput .='<li><time itemprop="startDate" datetime="2017-11-28T20:00:00+01:00">';
        $expectedOutput .='<span class="cf-from cf-meta">Du</span> <span class="cf-weekday cf-meta">mardi</span> ';
        $expectedOutput .='<span class="cf-date">28 novembre 2017</span> <span class="cf-at cf-meta">à</span> ';
        $expectedOutput .='<span class="cf-time">20:00</span></time> <span class="cf-to cf-meta">au</span> ';
        $expectedOutput .='<time itemprop="endDate" datetime="2017-11-30T22:00:00+01:00">';
        $expectedOutput .='<span class="cf-weekday cf-meta">jeudi</span> <span class="cf-date">30 novembre 2017</span> ';
        $expectedOutput .='<span class="cf-at cf-meta">à</span> <span class="cf-time">22:00</span></time></li></ul>';

        $this->assertEquals(
            $expectedOutput,
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

        $expectedOutput = '<ul class="cnw-event-date-info"><li>';
        $expectedOutput .= '<time itemprop="startDate" datetime="2017-11-09T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">9 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-09T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';
        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-16T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">16 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-16T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time> <span class="cf-status">(geannuleerd)</span></li>';
        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-23T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">23 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-23T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time> <span class="cf-status">(Volzet of uitverkocht)</span></li>';
        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-30T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">Donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">30 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-30T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li></ul>';

        $this->assertEquals(
            $expectedOutput,
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

        $expectedOutput = '<ul class="cnw-event-date-info"><li>';
        $expectedOutput .= '<time itemprop="startDate" datetime="2017-11-06T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">maandag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-09T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">9 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';

        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-14T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">dinsdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">14 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-16T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">16 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';

        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-21T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">dinsdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">21 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-23T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">23 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';

        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-28T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">dinsdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">28 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-30T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">30 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-at cf-meta">om</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li></ul>';

        $this->assertEquals(
            $expectedOutput,
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
