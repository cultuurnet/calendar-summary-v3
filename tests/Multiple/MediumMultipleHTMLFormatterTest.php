<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

class MediumMultipleHTMLFormatterTest extends TestCase
{
    /**
     * @var MediumMultipleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MediumMultipleHTMLFormatter(new Translator('nl_NL'), false);
    }

    public function testFormatHTMLMultipleDateMediumOneDay(): void
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
        $expectedOutput .= '<span class="cf-weekday cf-meta">Donderdag</span> ';
        $expectedOutput .= '<span class="cf-date">9 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-weekday cf-meta">Donderdag</span> ';
        $expectedOutput .= '<span class="cf-date">16 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-weekday cf-meta">Donderdag</span> ';
        $expectedOutput .= '<span class="cf-date">23 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-weekday cf-meta">Donderdag</span> ';
        $expectedOutput .= '<span class="cf-date">30 november 2017</span></li></ul>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLMultipleDateMediumMoreDays(): void
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
        $expectedOutput .= '<span class="cf-from cf-meta">Van</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">maandag</span> ';
        $expectedOutput .= '<span class="cf-date">6 november 2017</span> ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span> ';
        $expectedOutput .= '<span class="cf-date">9 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-from cf-meta">Van</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">dinsdag</span> ';
        $expectedOutput .= '<span class="cf-date">14 november 2017</span> ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span> ';
        $expectedOutput .= '<span class="cf-date">16 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-from cf-meta">Van</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">dinsdag</span> ';
        $expectedOutput .= '<span class="cf-date">21 november 2017</span> ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span> ';
        $expectedOutput .= '<span class="cf-date">23 november 2017</span></li>';
        $expectedOutput .= '<li><span class="cf-from cf-meta">Van</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">dinsdag</span> ';
        $expectedOutput .= '<span class="cf-date">28 november 2017</span> ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span> ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span> ';
        $expectedOutput .= '<span class="cf-date">30 november 2017</span></li></ul>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
