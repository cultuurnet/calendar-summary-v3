<?php

namespace CultuurNet\CalendarSummaryV3\Tests;

use CultuurNet\CalendarSummaryV3\CalendarPlainTextFormatter;
use CultuurNet\CalendarSummaryV3\FormatterException;
use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

class CalendarPlainTextFormatterTest extends TestCase
{
    /**
     * @var CalendarPlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new CalendarPlainTextFormatter();
    }

    public function testGeneralFormatMethod(): void
    {
        $offer = new Event();
        $offer->setCalendarType(Event::CALENDAR_TYPE_SINGLE);
        $offer->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $offer->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertIsString($this->formatter->format($offer, 'xs'));
    }

    public function testGeneralFormatMethodAndCatchException(): void
    {
        $offer = new Event();
        $offer->setCalendarType(Event::CALENDAR_TYPE_SINGLE);
        $offer->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $offer->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->expectException(FormatterException::class);
        $this->formatter->format($offer, 'sx');
    }
}
