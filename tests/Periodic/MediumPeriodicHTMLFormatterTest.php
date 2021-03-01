<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\Status;
use PHPUnit\Framework\TestCase;

/**
 * Provide unit tests for medium HTML periodic formatter.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
final class MediumPeriodicHTMLFormatterTest extends TestCase
{
    /**
     * @var MediumPeriodicHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MediumPeriodicHTMLFormatter(new Translator('nl_NL'));
    }

    public function testFormatAPeriodWithoutLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Available'));
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">25 november 2025</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">30 november 2030</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Available'));
        $offer->setStartDate(new \DateTime('04-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">4 maart 2025</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">8 maart 2030</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithoutLeadingZeroesWithUnavailableStatus(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Unavailable'));
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">25 november 2025</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">30 november 2030</span>'
            . ' <span class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodDayWithoutLeadingZero(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Available'));
        $offer->setStartDate(new \DateTime('25-03-2025'));
        $offer->setEndDate(new \DateTime('30-03-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">25 maart 2025</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">30 maart 2030</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodMonthWithoutLeadingZero(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Available'));
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $this->assertEquals(
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">4 oktober 2025</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">8 oktober 2030</span>',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithSameBeginAndEndDate(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Available'));
        $offer->setStartDate(new \DateTime('08-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2025'));

        $output = '<span class="cf-weekday cf-meta">Woensdag</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">8 oktober 2025</span>';

        $this->assertEquals(
            $output,
            $this->formatter->format($offer)
        );
    }
}
