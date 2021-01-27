<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

/**
 * Provide unit tests for extra small plain text periodic formatter.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class ExtraSmallPeriodicPlainTextFormatterTest extends TestCase
{
    /**
     * @var ExtraSmallPeriodicPlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new ExtraSmallPeriodicPlainTextFormatter(new Translator('nl_NL'));
    }

    public function testFormatAPeriodWithoutLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            'Vanaf 25/11/25',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2030'));

        $this->assertEquals(
            'Vanaf 4/3/25',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodDayWithoutLeadingZero(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-03-2025'));
        $offer->setEndDate(new \DateTime('30-03-2030'));

        $this->assertEquals(
            'Vanaf 25/3/25',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodMonthWithoutLeadingZero(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $this->assertEquals(
            'Vanaf 4/10/25',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodThatHasAlreadyStarted(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('12-03-2015'));
        $offer->setEndDate(new \DateTime('18-03-2030'));

        $this->assertEquals(
            'Tot 18/3/30',
            $this->formatter->format($offer)
        );
    }
}
