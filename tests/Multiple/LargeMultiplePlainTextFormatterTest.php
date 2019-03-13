<?php

namespace CultuurNet\CalendarSummaryV3\Tests\Multiple;

use CultuurNet\CalendarSummaryV3\Multiple\LargeMultiplePlainTextFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

class LargeMultiplePlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargeMultiplePlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        date_default_timezone_set('Europe/Brussels');
        $this->formatter = new LargeMultiplePlainTextFormatter('nl_NL', false);
    }

    public function testFormatPlainTextMultipleDateLargeOneDay()
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

        $expectedOutput = 'donderdag 9 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00' . PHP_EOL;

        $expectedOutput .= 'donderdag 16 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00' . PHP_EOL;

        $expectedOutput .= 'donderdag 23 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00' . PHP_EOL;

        $expectedOutput .= 'donderdag 30 november 2017';
        $expectedOutput .= ' van 20:00 tot 22:00';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextMultipleDateLargeMoreDays()
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

        $expectedOutput = 'Van maandag 6 november 2017 20:00 tot donderdag 9 november 2017 22:00' . PHP_EOL;
        $expectedOutput .= 'Van dinsdag 14 november 2017 20:00 tot donderdag 16 november 2017 22:00' . PHP_EOL;
        $expectedOutput .= 'Van dinsdag 21 november 2017 20:00 tot donderdag 23 november 2017 22:00' . PHP_EOL;
        $expectedOutput .= 'Van dinsdag 28 november 2017 20:00 tot donderdag 30 november 2017 22:00';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
