<?php

namespace CultuurNet\CalendarSummaryV3\Tests\Single;

use CultuurNet\CalendarSummaryV3\Single\SmallSinglePlainTextFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

class SmallSinglePlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SmallSingleHTMLFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new SmallSinglePlainTextFormatter('nl_NL');
    }

    public function testFormatPlainTextSingleDateXsOneDay()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            '25 jan',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsWithLeadingZeroOneDay()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-08T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            '8 jan',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsMoreDays()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-28T21:30:00+01:00'));

        $this->assertEquals(
            'Van 25 jan tot 28 jan',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsWithLeadingZeroMoreDays()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-06T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            'Van 6 jan tot 8 jan',
            $this->formatter->format($event)
        );
    }
}
