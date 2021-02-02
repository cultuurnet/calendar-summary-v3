<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\Status;
use PHPUnit\Framework\TestCase;

class MediumSinglePlainTextFormatterTest extends TestCase
{
    /**
     * @var MediumSinglePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MediumSinglePlainTextFormatter(new Translator('nl_NL'));
    }

    public function testFormatPlainTextSingleDateMediumOneDay(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Available'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            'Donderdag 25 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateMediumWithLeadingZeroOneDay(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Available'));
        $event->setStartDate(new \DateTime('2018-01-08T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            'Maandag 8 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateMediumMoreDays(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Available'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-27T21:30:00+01:00'));

        $this->assertEquals(
            'Van donderdag 25 januari 2018 tot zaterdag 27 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateMediumWithLeadingZeroMoreDays(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Available'));
        $event->setStartDate(new \DateTime('2018-01-06T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            'Van zaterdag 6 januari 2018 tot maandag 8 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateMediumOneDayWithUnavailableStatus(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Unavailable'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            'Donderdag 25 januari 2018 (geannuleerd)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateMediumOneDayWithTemporarilyUnavailableStatus(): void
    {
        $event = new Event();
        $event->setStatus(new Status('TemporarilyUnavailable'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            'Donderdag 25 januari 2018 (uitgesteld)',
            $this->formatter->format($event)
        );
    }
}
