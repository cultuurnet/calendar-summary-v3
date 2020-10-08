<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

/**
 * Provide unit tests for small plain text periodic formatter.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class SmallPeriodicPlainTextFormatterTest extends TestCase
{
    /**
     * @var SmallPeriodicPlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new SmallPeriodicPlainTextFormatter('nl_NL');
    }

    public function testFormatAPeriodWithoutLeadingZeroes()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            'Vanaf 25 nov 2025',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithLeadingZeroes()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2030'));

        $this->assertEquals(
            'Vanaf 4 mrt 2025',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodDayWithoutLeadingZero()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-03-2025'));
        $offer->setEndDate(new \DateTime('30-03-2030'));

        $this->assertEquals(
            'Vanaf 25 mrt 2025',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodMonthWithoutLeadingZero()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $this->assertEquals(
            'Vanaf 4 okt 2025',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodThatHasAlreadyStarted()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('12-03-2015'));
        $offer->setEndDate(new \DateTime('18-03-2030'));

        $this->assertEquals(
            'Tot 18 mrt 2030',
            $this->formatter->format($offer)
        );
    }
}
