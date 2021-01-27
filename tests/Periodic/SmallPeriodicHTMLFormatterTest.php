<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

/**
 * Provide unit tests for small HTML periodic formatter.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class SmallPeriodicHTMLFormatterTest extends TestCase
{
    /**
     * @var SmallPeriodicHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new SmallPeriodicHTMLFormatter(new Translator('nl_NL'));
    }

    public function testFormatAPeriodWithoutLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $expected =
            '<span class="from meta">Vanaf</span>' .
            ' ' .
            '<span class="cf-date">25</span>' .
            ' ' .
            '<span class="cf-month">nov</span>' .
            ' ' .
            '<span class="cf-year">2025</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2030'));

        $expected =
            '<span class="from meta">Vanaf</span>' .
            ' ' .
            '<span class="cf-date">4</span>' .
            ' ' .
            '<span class="cf-month">mrt</span>' .
            ' ' .
            '<span class="cf-year">2025</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }


    public function testFormatAPeriodDayWithoutLeadingZero(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-03-2025'));
        $offer->setEndDate(new \DateTime('30-03-2030'));

        $expected =
            '<span class="from meta">Vanaf</span>' .
            ' ' .
            '<span class="cf-date">25</span>' .
            ' ' .
            '<span class="cf-month">mrt</span>' .
            ' ' .
            '<span class="cf-year">2025</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodMonthWithoutLeadingZero(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $expected =
            '<span class="from meta">Vanaf</span>' .
            ' ' .
            '<span class="cf-date">4</span>' .
            ' ' .
            '<span class="cf-month">okt</span>' .
            ' ' .
            '<span class="cf-year">2025</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodThatHasAlreadyStarted(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('12-03-2015'));
        $offer->setEndDate(new \DateTime('18-03-2030'));

        $expected =
            '<span class="to meta">Tot</span>' .
            ' ' .
            '<span class="cf-date">18</span>' .
            ' ' .
            '<span class="cf-month">mrt</span>' .
            ' ' .
            '<span class="cf-year">2030</span>';

        $this->assertEquals(
            $expected,
            $this->formatter->format($offer)
        );
    }
}
