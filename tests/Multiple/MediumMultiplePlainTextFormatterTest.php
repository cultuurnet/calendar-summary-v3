<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\Status;
use PHPUnit\Framework\TestCase;

class MediumMultiplePlainTextFormatterTest extends TestCase
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
        $event = new Event();
        $event->setStatus(new Status('Available'));
        $newEvents = array();
        foreach ($subEvents as $subEvent) {
            $e = new Event();
            $e->setStatus(new Status('Available'));
            $e->setStartDate(new \DateTime($subEvent['startDate']));
            $e->setEndDate(new \DateTime($subEvent['endDate']));
            $newEvents[] = $e;
        }
        $event->setSubEvents($newEvents);

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
        $event = new Event();
        $event->setStatus(new Status('Available'));
        $newEvents = array();
        foreach ($subEvents as $subEvent) {
            $e = new Event();
            $e->setStatus(new Status('Available'));
            $e->setStartDate(new \DateTime($subEvent['startDate']));
            $e->setEndDate(new \DateTime($subEvent['endDate']));
            $newEvents[] = $e;
        }
        $event->setSubEvents($newEvents);

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
        $event = new Event();
        $event->setStatus(new Status('Unavailable'));
        $newEvents = array();
        foreach ($subEvents as $subEvent) {
            $e = new Event();
            $e->setStatus(new Status('Unavailable'));
            $e->setStartDate(new \DateTime($subEvent['startDate']));
            $e->setEndDate(new \DateTime($subEvent['endDate']));
            $newEvents[] = $e;
        }
        $event->setSubEvents($newEvents);

        $expectedOutput = 'Donderdag 9 november 2017 (geannuleerd)'. PHP_EOL;
        $expectedOutput .= 'Donderdag 16 november 2017 (geannuleerd)' . PHP_EOL;
        $expectedOutput .= 'Donderdag 23 november 2017 (geannuleerd)' . PHP_EOL;
        $expectedOutput .= 'Donderdag 30 november 2017 (geannuleerd)';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
