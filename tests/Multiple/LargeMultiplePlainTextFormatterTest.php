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

final class LargeMultiplePlainTextFormatterTest extends TestCase
{
    /**
     * @var LargeMultiplePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        date_default_timezone_set('Europe/Brussels');
        $this->formatter = new LargeMultiplePlainTextFormatter(new Translator('nl_NL'), false);
    }

    public function testFormatPlainTextMultipleDateLargeOneDay(): void
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

        $expectedOutput = 'Donderdag 9 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00' . PHP_EOL;

        $expectedOutput .= 'Donderdag 16 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00' . PHP_EOL;

        $expectedOutput .= 'Donderdag 23 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00' . PHP_EOL;

        $expectedOutput .= 'Donderdag 30 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextMultipleDateLargeOneDayWithUnavailableStatus(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEvents.json'), true);
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::multiple()
        );

        $newEvents = [];
        foreach ($subEvents as $subEvent) {
            $newEvents[] = new Offer(
                OfferType::event(),
                new Status('Unavailable', []),
                new BookingAvailability('Available'),
                new DateTimeImmutable($subEvent['startDate']),
                new DateTimeImmutable($subEvent['endDate'])
            );
        }

        $event = $event->withSubEvents($newEvents);

        $expectedOutput = 'Donderdag 9 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00 (geannuleerd)' . PHP_EOL;

        $expectedOutput .= 'Donderdag 16 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00 (geannuleerd)' . PHP_EOL;

        $expectedOutput .= 'Donderdag 23 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00 (geannuleerd)' . PHP_EOL;

        $expectedOutput .= 'Donderdag 30 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00 (geannuleerd)';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextMultipleDateLargeMoreDays(): void
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

        $expectedOutput = 'Van maandag 6 november 2017 om 20:00 tot en met donderdag 9 november 2017 om 22:00' . PHP_EOL;
        $expectedOutput .= 'Van dinsdag 14 november 2017 om 20:00 tot en met donderdag 16 november 2017 om 22:00' . PHP_EOL;
        $expectedOutput .= 'Van dinsdag 21 november 2017 om 20:00 tot en met donderdag 23 november 2017 om 22:00' . PHP_EOL;
        $expectedOutput .= 'Van dinsdag 28 november 2017 om 20:00 tot en met donderdag 30 november 2017 om 22:00';

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

        $expectedOutput = 'Du lundi 6 novembre 2017 à 20:00 au jeudi 9 novembre 2017 à 22:00' . PHP_EOL;
        $expectedOutput .= 'Du mardi 14 novembre 2017 à 20:00 au jeudi 16 novembre 2017 à 22:00' . PHP_EOL;
        $expectedOutput .= 'Du mardi 21 novembre 2017 à 20:00 au jeudi 23 novembre 2017 à 22:00' . PHP_EOL;
        $expectedOutput .= 'Du mardi 28 novembre 2017 à 20:00 au jeudi 30 novembre 2017 à 22:00';

        $this->assertEquals(
            $expectedOutput,
            (new LargeMultiplePlainTextFormatter(new Translator('fr'), false))->format($event)
        );
    }

    public function testItWillShowEventHasConcludedWhenAllPastDatesAreHidden(): void
    {
        $formatter = new LargeMultiplePlainTextFormatter(new Translator('nl_NL'), true);

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

        $expectedOutput = 'Evenement afgelopen';

        $this->assertEquals(
            $expectedOutput,
            $formatter->format($event)
        );
    }
}
