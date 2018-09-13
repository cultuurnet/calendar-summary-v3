<?php

namespace CultuurNet\CalendarSummaryV3\Tests\Multiple;

use CultuurNet\CalendarSummaryV3\Multiple\LargeMultipleHTMLFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

class LargeMultipleHTMLFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargeMultipleHTMLFormatter
     */
    protected $formatter;

    public function setUp()
    {
        date_default_timezone_set('Europe/Brussels');
        $this->formatter = new LargeMultipleHTMLFormatter('nl_NL', false);
    }

    public function testFormatHTMLMultipleDateLargeOneDay()
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

        $expectedOutput = '<ul class="cnw-event-date-info"><li>';
        $expectedOutput .= '<time itemprop="startDate" datetime="2017-11-09T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">9 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-09T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';
        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-16T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">16 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-16T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';
        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-23T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">23 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-23T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';
        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-30T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">30 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-from cf-meta">van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-30T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li></ul>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLMultipleDateLargeMoreDays()
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

        $expectedOutput = '<ul class="cnw-event-date-info"><li>';
        $expectedOutput .= '<time itemprop="startDate" datetime="2017-11-06T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">maandag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-09T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">9 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';

        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-14T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">dinsdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">14 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-16T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">16 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';

        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-21T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">dinsdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">21 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-23T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">23 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li>';

        $expectedOutput .= '<li><time itemprop="startDate" datetime="2017-11-28T20:00:00+01:00">';
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">dinsdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">28 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">20:00</span>';
        $expectedOutput .= '</time>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<time itemprop="endDate" datetime="2017-11-30T22:00:00+01:00">';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">30 november 2017</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-time">22:00</span>';
        $expectedOutput .= '</time></li></ul>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
