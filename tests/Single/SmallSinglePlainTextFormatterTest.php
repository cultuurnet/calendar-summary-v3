<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

class SmallSinglePlainTextFormatterTest extends TestCase
{
    /**
     * @var SmallSinglePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new SmallSinglePlainTextFormatter('nl_NL');
    }

    public function testFormatPlainTextSingleDateXsOneDay(): void
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            '25 jan',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsWithLeadingZeroOneDay(): void
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-08T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            '8 jan',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsMoreDays(): void
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-28T21:30:00+01:00'));

        $this->assertEquals(
            'Van 25 jan tot 28 jan',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsWithLeadingZeroMoreDays(): void
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
