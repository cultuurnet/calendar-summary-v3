<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Event;

/**
 * Provide unit tests for medium plain text periodic formatter.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class MediumPeriodicPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediumPeriodicPlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new MediumPeriodicPlainTextFormatter('nl_NL');
    }

    public function testFormatAPeriodWithoutLeadingZeroes()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            'Van 25 november 2025 tot en met 30 november 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithLeadingZeroes()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2030'));

        $this->assertEquals(
            'Van 4 maart 2025 tot en met 8 maart 2030',
            $this->formatter->format($offer)
        );
    }


    public function testFormatAPeriodDayWithoutLeadingZero()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-03-2025'));
        $offer->setEndDate(new \DateTime('30-03-2030'));

        $this->assertEquals(
            'Van 25 maart 2025 tot en met 30 maart 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodMonthWithoutLeadingZero()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $this->assertEquals(
            'Van 4 oktober 2025 tot en met 8 oktober 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithSameBeginAndEndDate()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('08-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2025'));

        $this->assertEquals(
            'woensdag 8 oktober 2025',
            $this->formatter->format($offer)
        );
    }
}
