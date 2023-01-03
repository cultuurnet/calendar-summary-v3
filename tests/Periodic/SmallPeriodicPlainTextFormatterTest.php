<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\CalendarSummaryTester;
use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class SmallPeriodicPlainTextFormatterTest extends TestCase
{
    /**
     * @var SmallPeriodicPlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new SmallPeriodicPlainTextFormatter(new Translator('nl_NL'));
        CalendarSummaryTester::setTestNow(2021, 5, 3);
    }

    public function testFormatAPeriodStartsCurrentYear(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2021'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            'Vanaf do 25 nov',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodEndsCurrentYear(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2015'),
            new DateTimeImmutable('22-02-2021'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            'Tot ma 22 feb',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithoutLeadingZeroes(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            'Vanaf di 25 nov 2025',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithLeadingZeroes(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('04-03-2025'),
            new DateTimeImmutable('08-03-2030'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            'Vanaf di 4 mrt 2025',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodWithoutLeadingZeroesWithUnavailableStatus(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            'Vanaf di 25 nov 2025 (geannuleerd)',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodDayWithoutLeadingZero(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-03-2025'),
            new DateTimeImmutable('30-03-2030'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            'Vanaf di 25 mrt 2025',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodMonthWithoutLeadingZero(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('04-10-2025'),
            new DateTimeImmutable('08-10-2030'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            'Vanaf za 4 okt 2025',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodThatHasAlreadyStarted(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('12-03-2015'),
            new DateTimeImmutable('18-03-2030'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            'Tot ma 18 mrt 2030',
            $this->formatter->format($offer)
        );
    }

    public function testFormatAPeriodThatHasAlreadyStartedWithUnavailableStatus(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('12-03-2015'),
            new DateTimeImmutable('18-03-2030'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            'Tot ma 18 mrt 2030 (geannuleerd)',
            $this->formatter->format($offer)
        );
    }
}
