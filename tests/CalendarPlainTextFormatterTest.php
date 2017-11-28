<?php

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\SearchV3\ValueObjects\Event;

class CalendarPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CalendarPlainTextFormatter
     */
    protected $formatter;


    public function setUp()
    {
        $this->formatter = new CalendarPlainTextFormatter();
    }

    public function testGeneralFormatMethod()
    {
        $offer = new Event();
        $offer->setCalendarType(Event::CALENDAR_TYPE_SINGLE);
        $offer->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $offer->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->formatter->format($offer, 'xs');
    }

    public function testGeneralFormatMethodAndCatchException()
    {
        $offer = new Event();
        $offer->setCalendarType(Event::CALENDAR_TYPE_SINGLE);
        $offer->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $offer->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->expectException(FormatterException::class);
        $this->formatter->format($offer, 'sx');
    }
}
