<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\Status;
use PHPUnit\Framework\TestCase;

final class ExtraSmallMultiplePlainTextFormatterTest extends TestCase
{
    /**
     * @var ExtraSmallMultiplePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new ExtraSmallMultiplePlainTextFormatter(new Translator('nl_NL'));
    }

    public function testFormatMultipleWithoutLeadingZeroes(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Available'));
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            'Van 25/11/25 tot 30/11/30',
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
            'Van 4/3/25 tot 8/3/30',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleWithUnavailableStatus(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Unavailable'));
        $offer->setStartDate(new \DateTime('25-11-2025'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            'Van 25/11/25 tot 30/11/30 (geannuleerd)',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleOnSameDayWithUnavailableStatus(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Unavailable'));
        $offer->setStartDate(new \DateTime('30-11-2030'));
        $offer->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            '30/11/30 (geannuleerd)',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleDayWithoutLeadingZero(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Available'));
        $offer->setStartDate(new \DateTime('25-03-2025'));
        $offer->setEndDate(new \DateTime('30-03-2030'));

        $this->assertEquals(
            'Van 25/3/25 tot 30/3/30',
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
            'Van 4/10/25 tot 8/10/30',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithSameBeginAndEndDate(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Available'));
        $offer->setStartDate(new \DateTime('08-03-2025'));
        $offer->setEndDate(new \DateTime('08-03-2025'));

        $this->assertEquals(
            '8/3/25',
            $this->formatter->format($offer)
        );
    }

    public function testFormatMultipleMonthWithUnavailableStatus(): void
    {
        $offer = new Event();
        $offer->setStatus(new Status('Unavailable'));
        $offer->setStartDate(new \DateTime('04-10-2025'));
        $offer->setEndDate(new \DateTime('08-10-2030'));

        $this->assertEquals(
            'Van 4/10/25 tot 8/10/30 (geannuleerd)',
            $this->formatter->format($offer)
        );
    }
}
