<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

class ExtraSmallMultipleHTMLFormatterTest extends TestCase
{
    /**
     * @var ExtraSmallMultipleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new ExtraSmallMultipleHTMLFormatter('nl_NL');
    }

    public function testFormatMultipleWithoutLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">25/11/25</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">30/11/30</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleWithLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">4/3/25</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">8/3/30</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleMonthWithoutLeadingZero(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">4/10/25</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">8/10/30</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAMultipleWithSameBeginAndEndDate(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('08-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2025'));

        $output = '<span class="cf-date">8/10/25</span>';

        $this->assertEquals(
            $output,
            $this->formatter->format($offer)
        );
    }
}
