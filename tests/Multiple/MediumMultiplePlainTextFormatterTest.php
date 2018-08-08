<?php

namespace CultuurNet\CalendarSummaryV3\Tests\Single;

use CultuurNet\CalendarSummaryV3\Multiple\MediumMultiplePlainTextFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

class MediumMultiplePlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediumMultiplePlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new MediumMultiplePlainTextFormatter('nl_NL');
    }

    public function testFormatPlainTextMultipleDateMediumOneDay()
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEvents.json'), true);
        $event = new Event();
        $newEvents = array();
        foreach($subEvents as $subEvent) {
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

    public function testFormatPlainTextMultipleDateMediumMoreDays()
    {
        $subEvents = json_decode(file_get_contents(__DIR__ . '/data/subEventsMoreDays.json'), true);
        $event = new Event();
        $newEvents = array();
        foreach($subEvents as $subEvent) {
            $e = new Event();
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
}
