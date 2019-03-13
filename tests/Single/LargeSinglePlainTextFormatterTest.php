<?php

namespace CultuurNet\CalendarSummaryV3\Tests\Single;

use CultuurNet\CalendarSummaryV3\Single\LargeSinglePlainTextFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

class LargeSinglePlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargeSinglePlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        date_default_timezone_set('Europe/Brussels');
        $this->formatter = new LargeSinglePlainTextFormatter('nl_NL');
    }

    public function testFormatPlainTextSingleDateLargeOneDay()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $expectedOutput = 'donderdag 25 januari 2018';
        $expectedOutput .= ' van 20:00 tot 21:30';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateLargeWithLeadingZeroOneDay()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-08T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $expectedOutput = 'maandag 8 januari 2018';
        $expectedOutput .= ' van 20:00 tot 21:30';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateLargeMoreDays()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-28T21:30:00+01:00'));

        $expectedOutput = 'Van donderdag 25 januari 2018 20:00';
        $expectedOutput .= ' tot zondag 28 januari 2018 21:30';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateLargeWithLeadingZeroMoreDays()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-06T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $expectedOutput = 'Van zaterdag 6 januari 2018 20:00';
        $expectedOutput .= ' tot maandag 8 januari 2018 21:30';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateLargeWholeDay()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-06T00:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-06T23:59:59+01:00'));

        $expectedOutput = 'zaterdag 6 januari 2018';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateLargeSameTime()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-06T13:30:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-06T13:30:00+01:00'));

        $expectedOutput = 'zaterdag 6 januari 2018 om 13:30';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
