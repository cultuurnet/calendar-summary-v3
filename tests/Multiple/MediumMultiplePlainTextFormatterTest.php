<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

class MediumMultiplePlainTextFormatterTest extends TestCase
{
    /**
     * @var MediumMultiplePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MediumMultiplePlainTextFormatter('nl_NL', false);
    }

    public function testFormatPlainTextMultipleDateMediumOneDay(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEvents.json'), true);
        $event = new Event();
        $newEvents = array();
        foreach ($subEvents as $subEvent) {
            $e = new Event();
            $e->setStartDate(new \DateTime($subEvent['startDate']));
            $e->setEndDate(new \DateTime($subEvent['endDate']));
            $newEvents[] = $e;
        }
        $event->setSubEvents($newEvents);

        $expectedOutput = 'donderdag 9 november 2017' . PHP_EOL;
        $expectedOutput .= 'donderdag 16 november 2017' . PHP_EOL;
        $expectedOutput .= 'donderdag 23 november 2017' . PHP_EOL;
        $expectedOutput .= 'donderdag 30 november 2017';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextMultipleDateMediumMoreDays(): void
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEventsMoreDays.json'), true);
        $event = new Event();
        $newEvents = array();
        foreach ($subEvents as $subEvent) {
            $e = new Event();
            $e->setStartDate(new \DateTime($subEvent['startDate']));
            $e->setEndDate(new \DateTime($subEvent['endDate']));
            $newEvents[] = $e;
        }
        $event->setSubEvents($newEvents);

        $expectedOutput = 'van maandag 6 november 2017 tot donderdag 9 november 2017' . PHP_EOL;
        $expectedOutput .= 'van dinsdag 14 november 2017 tot donderdag 16 november 2017' . PHP_EOL;
        $expectedOutput .= 'van dinsdag 21 november 2017 tot donderdag 23 november 2017' . PHP_EOL;
        $expectedOutput .= 'van dinsdag 28 november 2017 tot donderdag 30 november 2017';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
