<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\Status;
use PHPUnit\Framework\TestCase;

final class SmallSinglePlainTextFormatterTest extends TestCase
{
    /**
     * @var SmallSinglePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new SmallSinglePlainTextFormatter(new Translator('nl_NL'));
    }

    public function testFormatPlainTextSingleDateXsOneDay(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Available'));
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
        $event->setStatus(new Status('Available'));
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
        $event->setStatus(new Status('Available'));
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
        $event->setStatus(new Status('Available'));
        $event->setStartDate(new \DateTime('2018-01-06T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            'Van 6 jan tot 8 jan',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsOneDayWithStatusUnavailable(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Unavailable'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            '25 jan (geannuleerd)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsOneDayWithStatusTemporarilyUnavailable(): void
    {
        $event = new Event();
        $event->setStatus(new Status('TemporarilyUnavailable'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            '25 jan (uitgesteld)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsMoreDaysWithStatusUnavailable(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Unavailable'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-28T21:30:00+01:00'));

        $this->assertEquals(
            'Van 25 jan tot 28 jan (geannuleerd)',
            $this->formatter->format($event)
        );
    }

    public function testFormatPlainTextSingleDateXsMoreDaysWithStatusTemporarilyUnavailable(): void
    {
        $event = new Event();
        $event->setStatus(new Status('TemporarilyUnavailable'));
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-28T21:30:00+01:00'));

        $this->assertEquals(
            'Van 25 jan tot 28 jan (uitgesteld)',
            $this->formatter->format($event)
        );
    }
}
