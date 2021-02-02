<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\Status;
use CultuurNet\SearchV3\ValueObjects\TranslatedString;
use PHPUnit\Framework\TestCase;

class SmallSingleHTMLFormatterTest extends TestCase
{
    /**
     * @var SmallSingleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new SmallSingleHTMLFormatter(new Translator('nl_NL'));
    }

    public function testFormatHTMLSingleDateXsOneDay(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Available'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            '<span class="cf-date">25</span> <span class="cf-month">jan</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsOneDayWithStatusUnavailable(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Unavailable'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            '<span class="cf-date">25</span> <span class="cf-month">jan</span> <span class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsOneDayWithStatusUnavailableAndReason(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Unavailable', new TranslatedString(['nl' => 'Covid-19'])));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            '<span class="cf-date">25</span> <span class="cf-month">jan</span> <span title="Covid-19" class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsWithLeadingZeroOneDay(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Available'));
        $event->setStartDate(new \DateTime('2018-01-08T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            '<span class="cf-date">8</span> <span class="cf-month">jan</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsMoreDays(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Available'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-27T21:30:00+01:00'));

        $expectedOutput = '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">25</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">27</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsMoreDaysWithUnavailableStatus(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Unavailable'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-27T21:30:00+01:00'));

        $expectedOutput = '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">25</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">27</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-status">(geannuleerd)</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateXsWithLeadingZeroMoreDays(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Available'));
        $event->setStartDate(new \DateTime('2018-01-06T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $expectedOutput = '<span class="cf-from cf-meta">Van</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">6</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-to cf-meta">tot</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-date">8</span>';
        $expectedOutput .= ' ';
        $expectedOutput .= '<span class="cf-month">jan</span>';

        $this->assertEquals(
            $expectedOutput,
            $this->formatter->format($event)
        );
    }
}
