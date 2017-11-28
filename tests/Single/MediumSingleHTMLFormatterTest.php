<?php

namespace CultuurNet\CalendarSummaryV3\Tests\Single;

use CultuurNet\CalendarSummaryV3\Single\MediumSingleHTMLFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

class MediumSingleHTMLFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediumSingleHTMLFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new MediumSingleHTMLFormatter();
    }

    public function testFormatHTMLSingleDateXs()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            '<span class="cf-weekday cf-meta">donderdag</span> <span class="cf-date">25 januari 2018</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsWithLeadingZero()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-08T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            '<span class="cf-weekday cf-meta">maandag</span> <span class="cf-date">8 januari 2018</span>',
            $this->formatter->format($event)
        );
    }
}
