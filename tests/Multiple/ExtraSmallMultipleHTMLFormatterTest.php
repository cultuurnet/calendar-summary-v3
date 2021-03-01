<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\Status;
use PHPUnit\Framework\TestCase;

final class ExtraSmallMultipleHTMLFormatterTest extends TestCase
{
    /**
     * @var ExtraSmallMultipleHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new ExtraSmallMultipleHTMLFormatter(new Translator('nl_NL'));
    }

    public function testFormatMultipleWithoutLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Available'));
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
        $offer->setStatus(new Status('Available'));
        $offer->setStartDate(new \DateTime('04-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">4/3/25</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">8/3/30</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleWithoutLeadingZeroesWithUnavailableStatus(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Unavailable'));
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">25/11/25</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">30/11/30</span>'
            . ' <span class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleMonthWithoutLeadingZero(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Available'));
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">4/10/25</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">8/10/30</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAMultipleWithSameBeginAndEndDay(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Available'));
        $offer->setStartDate(new \DateTime('08-10-2025 12:00'));
        $offer->setEndDate(new \DateTime('08-10-2025 14:00'));

        $output = '<span class="cf-date">8/10/25</span>';

        $this->assertEquals(
            $output,
            $this->formatter->format($offer)
        );
    }
}
