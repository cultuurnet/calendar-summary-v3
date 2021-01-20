<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

class SmallMultiplePlainTextFormatterTest extends TestCase
{
    /**
     * @var SmallMultiplePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new SmallMultiplePlainTextFormatter('nl_NL');
    }

    public function testFormatMultipleWithoutLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            'Van 25 november 2025 tot 30 november 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleWithLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2030'));

        $this->assertEquals(
            'Van 4 maart 2025 tot 8 maart 2030',
            $this->formatter->format($offer)
        );
    }


    public function testFormatMultipleDayWithoutLeadingZero(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('25-03-2025'));
        $offer->setEndDate(new \DateTime('30-03-2030'));

        $this->assertEquals(
            'Van 25 maart 2025 tot 30 maart 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleMonthWithoutLeadingZero(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $this->assertEquals(
            'Van 4 oktober 2025 tot 8 oktober 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithSameBeginAndEndDate(): void
    {
        $offer = new Event();
        $offer->setStartDate(new \DateTime('08-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2025'));

        $this->assertEquals(
            'Woensdag 8 oktober 2025',
            $this->formatter->format($offer)
        );
    }
}
