<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

/**
 * Provide unit tests for extra small HTML periodic formatter.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class ExtraSmallPeriodicHTMLFormatterTest extends TestCase
{
    /**
     * @var ExtraSmallPeriodicHTMLFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new ExtraSmallPeriodicHTMLFormatter('nl_NL');
    }

    public function testFormatAPeriodWithoutLeadingZeroes()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            '<span class="from meta">Vanaf</span> <span class="cf-date">25</span>/<span class="cf-month">11</span>/<span class="cf-year">25</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithLeadingZeroes()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2030'));

        $this->assertEquals(
            '<span class="from meta">Vanaf</span> <span class="cf-date">4</span>/<span class="cf-month">3</span>/<span class="cf-year">25</span>',
            $this->formatter->format($offer)
        );
    }


    public function testFormatAPeriodDayWithoutLeadingZero()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-03-2025'));
        $offer->setEndDate(new \DateTime('30-03-2030'));

        $this->assertEquals(
            '<span class="from meta">Vanaf</span> <span class="cf-date">25</span>/<span class="cf-month">3</span>/<span class="cf-year">25</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodMonthWithoutLeadingZero()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $this->assertEquals(
            '<span class="from meta">Vanaf</span> <span class="cf-date">4</span>/<span class="cf-month">10</span>/<span class="cf-year">25</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodThatHasAlreadyStarted()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('12-03-2015'));
        $offer->setEndDate(new \DateTime('18-03-2030'));

        $this->assertEquals(
            '<span class="to meta">Tot</span> <span class="cf-date">18</span>/<span class="cf-month">3</span>/<span class="cf-year">30</span>',
            $this->formatter->format($offer)
        );
    }
}
