<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Event;

class ExtraSmallPeriodicPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExtraSmallPeriodicPlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new ExtraSmallPeriodicPlainTextFormatter();
    }

    public function testFormatAPeriodWithoutLeadingZeroes()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            'Vanaf 25/11',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithLeadingZeroes()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2030'));

        $this->assertEquals(
            'Vanaf 4/3',
            $this->formatter->format($offer)
        );
    }


    public function testFormatAPeriodDayWithoutLeadingZero()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-03-2025'));
        $offer->setEndDate(new \DateTime('30-03-2030'));

        $this->assertEquals(
            'Vanaf 25/3',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodMonthWithoutLeadingZero()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $this->assertEquals(
            'Vanaf 4/10',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodThatHasAlreadyStarted()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('12-03-2015'));
        $offer->setEndDate(new \DateTime('18-03-2030'));

        $this->assertEquals(
            'Tot 18/3',
            $this->formatter->format($offer)
        );
    }
}
