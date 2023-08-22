<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CalendarHTMLFormatterTest extends TestCase
{
    /**
     * @var CalendarHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new CalendarHTMLFormatter();
    }

    public function testGeneralFormatMethod(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00'),
            CalendarType::single()
        );

        $this->assertSame(
            '<span class="cf-weekday cf-meta">Do</span> <span class="cf-date">25</span> <span class="cf-month">jan</span> <span class="cf-year">2018</span>',
            $this->formatter->format($offer, 'xs')
        );
    }

    public function testGeneralFormatMethodAndCatchException(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00'),
            CalendarType::single()
        );

        $this->expectException(FormatterException::class);
        $this->formatter->format($offer, 'sx');
    }
}
