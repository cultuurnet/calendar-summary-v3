<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

class MediumSingleHTMLFormatterTest extends TestCase
{
    /**
     * @var MediumSingleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MediumSingleHTMLFormatter('nl_NL');
    }

    public function testFormatHTMLSingleDateMediumOneDay(): void
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            '<span class="cf-weekday cf-meta">Donderdag</span> <span class="cf-date">25 januari 2018</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateMediumWithLeadingZeroOneDay(): void
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-08T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            '<span class="cf-weekday cf-meta">Maandag</span> <span class="cf-date">8 januari 2018</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateMediumMoreDays(): void
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-28T21:30:00+01:00'));

        $expectedOutput = '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">donderdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">25 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">zondag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">28 januari 2018</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateMediumWithLeadingZeroMoreDays(): void
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-06T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $expectedOutput = '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">zaterdag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6 januari 2018</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-weekday cf-meta">maandag</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">8 januari 2018</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
