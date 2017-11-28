<?php

namespace CultuurNet\CalendarSummaryV3\Tests\Single;

use CultuurNet\CalendarSummaryV3\Single\MediumSinglePlainTextFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

class MediumSinglePlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediumSinglePlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new MediumSinglePlainTextFormatter();
    }

    public function testFormatHTMLSingleDateMedium()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            'donderdag 25 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateMediumWithLeadingZero()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-08T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            'maandag 8 januari 2018',
            $this->formatter->format($event)
        );
    }
}
