<?php

namespace CultuurNet\CalendarSummaryV3\Tests\Single;

use CultuurNet\CalendarSummaryV3\Single\LargeSingleHTMLFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

class LargeSingleHTMLFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargeSingleHTMLFormatter
     */
    protected $formatter;

    public function setUp()
    {
        date_default_timezone_set('Europe/Brussels');
        $this->formatter = new LargeSingleHTMLFormatter('nl_NL');
    }

    public function testFormatHTMLSingleDateLargeOneDay()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));
        
        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-25T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">25 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2018-01-25T21:30:00+01:00">';
        $expectedOutput .= '<span class="cf-time">21:30</span>';
        $expectedOutput .= '</time>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateLargeWithLeadingZeroOneDay()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-08T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-08T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">maandag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">8 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2018-01-08T21:30:00+01:00">';
        $expectedOutput .= '<span class="cf-time">21:30</span>';
        $expectedOutput .= '</time>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateLargeMoreDays()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-28T21:30:00+01:00'));

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-25T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">25 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2018-01-28T21:30:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">zondag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">28 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">21:30</span>';
        $expectedOutput .= '</time>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateLargeWithLeadingZerosMoreDays()
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-06T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $expectedOutput = '<time itemprop="startDate" datetime="2018-01-06T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">zaterdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2018-01-08T21:30:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">maandag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">8 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">21:30</span>';
        $expectedOutput .= '</time>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
