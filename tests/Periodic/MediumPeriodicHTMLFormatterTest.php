<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Event;

/**
 * Provide unit tests for medium HTML periodic formatter.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class MediumPeriodicHTMLFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediumPeriodicHTMLFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new MediumPeriodicHTMLFormatter('nl_NL');
    }

    public function testFormatAPeriodWithoutLeadingZeroes()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">25 november 2025</span> '
            . '<span class="cf-to cf-meta">tot en met</span> <span class="cf-date">30 november 2030</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithLeadingZeroes()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">4 maart 2025</span> '
            . '<span class="cf-to cf-meta">tot en met</span> <span class="cf-date">8 maart 2030</span>',
            $this->formatter->format($offer)
        );
    }


    public function testFormatAPeriodDayWithoutLeadingZero()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-03-2025'));
        $offer->setEndDate(new \DateTime('30-03-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">25 maart 2025</span> '
            . '<span class="cf-to cf-meta">tot en met</span> <span class="cf-date">30 maart 2030</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodMonthWithoutLeadingZero()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">4 oktober 2025</span> '
            . '<span class="cf-to cf-meta">tot en met</span> <span class="cf-date">8 oktober 2030</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithSameBeginAndEndDate()
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('08-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2025'));

        $output = '<span class="cf-weekday cf-meta">woensdag</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">8 oktober 2025</span>';

        $this->assertEquals(
            $output,
            $this->formatter->format($offer)
        );
    }
}
