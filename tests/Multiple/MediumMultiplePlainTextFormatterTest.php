<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class MediumMultiplePlainTextFormatterTest extends TestCase
{
    /**
     * @var MediumMultiplePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MediumMultiplePlainTextFormatter(new Translator('nl_NL'), false);
    }

    public function testFormatPlainTextMultipleDateMediumOneDay(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEvents.json'), true);
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            null,
            null,
            CalendarType::multiple()
        );

        $newEvents = [];
        foreach ($subEvents as $subEvent) {
            $newEvents[] = new Offer(
                OfferType::event(),
                new Status('Available', []),
                new DateTimeImmutable($subEvent['startDate']),
                new DateTimeImmutable($subEvent['endDate'])
            );
        }

        $event = $event->withSubEvents($newEvents);

        $expectedOutput = 'Donderdag 9 november 2017' . PHP_EOL;
        $expectedOutput .= 'Donderdag 16 november 2017' . PHP_EOL;
        $expectedOutput .= 'Donderdag 23 november 2017' . PHP_EOL;
        $expectedOutput .= 'Donderdag 30 november 2017';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextMultipleDateMediumMoreDays(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEventsMoreDays.json'), true);
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            null,
            null,
            CalendarType::multiple()
        );

        $newEvents = [];
        foreach ($subEvents as $subEvent) {
            $newEvents[] = new Offer(
                OfferType::event(),
                new Status('Available', []),
                new DateTimeImmutable($subEvent['startDate']),
                new DateTimeImmutable($subEvent['endDate'])
            );
        }

        $event = $event->withSubEvents($newEvents);

        $expectedOutput = 'Van maandag 6 november 2017 tot donderdag 9 november 2017' . PHP_EOL;
        $expectedOutput .= 'Van dinsdag 14 november 2017 tot donderdag 16 november 2017' . PHP_EOL;
        $expectedOutput .= 'Van dinsdag 21 november 2017 tot donderdag 23 november 2017' . PHP_EOL;
        $expectedOutput .= 'Van dinsdag 28 november 2017 tot donderdag 30 november 2017';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextMultipleDateMediumOneDayWithUnavailableStatus(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEvents.json'), true);
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            null,
            null,
            CalendarType::multiple()
        );

        $newEvents = [];
        foreach ($subEvents as $subEvent) {
            $newEvents[] = new Offer(
                OfferType::event(),
                new Status('Unavailable', []),
                new DateTimeImmutable($subEvent['startDate']),
                new DateTimeImmutable($subEvent['endDate'])
            );
        }

        $event = $event->withSubEvents($newEvents);

        $expectedOutput = 'Donderdag 9 november 2017 (geannuleerd)' . PHP_EOL;
        $expectedOutput .= 'Donderdag 16 november 2017 (geannuleerd)' . PHP_EOL;
        $expectedOutput .= 'Donderdag 23 november 2017 (geannuleerd)' . PHP_EOL;
        $expectedOutput .= 'Donderdag 30 november 2017 (geannuleerd)';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextMultipleDateMediumOneDayWithUnavailableStatusForSingleSubEvent(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEvents.json'), true);
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            null,
            null,
            CalendarType::multiple()
        );

        $newEvents = [];
        foreach ($subEvents as $subEvent) {
            $newEvents[] = new Offer(
                OfferType::event(),
                new Status('Available', []),
                new DateTimeImmutable($subEvent['startDate']),
                new DateTimeImmutable($subEvent['endDate'])
            );
        }

        $newEvents[1]->setStatus(new Status('Unavailable', []));

        $event = $event->withSubEvents($newEvents);

        $expectedOutput = 'Donderdag 9 november 2017' . PHP_EOL;
        $expectedOutput .= 'Donderdag 16 november 2017 (geannuleerd)' . PHP_EOL;
        $expectedOutput .= 'Donderdag 23 november 2017' . PHP_EOL;
        $expectedOutput .= 'Donderdag 30 november 2017';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
