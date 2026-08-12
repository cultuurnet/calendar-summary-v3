<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\PlainTextFixture;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class LargePeriodicPlainTextFormatterTest extends TestCase
{
    use PlainTextFixture;

    /**
     * @var LargePeriodicPlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new LargePeriodicPlainTextFormatter(new Translator('nl_NL'));
    }

    public function testFormatAPeriodWithSingleTimeBlocks(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $place = $place->withOpeningHours(
            [
                new OpeningHour(
                    ['monday','tuesday', 'wednesday'],
                    '00:01',
                    '17:00'
                ),
                new OpeningHour(
                    ['friday', 'saturday'],
                    '10:00',
                    '18:00'
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedText('periodWithSingleTimeBlocks'),
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithSingleTimeBlocksInFrench(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $place = $place->withOpeningHours(
            [
                new OpeningHour(
                    ['monday'],
                    '00:01',
                    '17:00'
                ),
                new OpeningHour(
                    ['friday'],
                    '10:00',
                    '18:00'
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedText('periodWithSingleTimeBlocksInFrench'),
            (new LargePeriodicPlainTextFormatter(new Translator('fr')))->format($place)
        );
    }

    public function testFormatAPeriodWithSingleTimeBlocksWithUnavailableStatus(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $event = $event->withOpeningHours(
            [
                new OpeningHour(
                    ['monday','tuesday', 'wednesday'],
                    '00:01',
                    '17:00'
                ),
                new OpeningHour(
                    ['friday', 'saturday'],
                    '10:00',
                    '18:00'
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedText('periodWithSingleTimeBlocksWithUnavailableStatus'),
            $this->formatter->format($event)
        );
    }

    public function testFormatAPeriodWithSplitTimeBlocks(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $place = $place->withOpeningHours(
            [
                new OpeningHour(
                    ['monday','tuesday', 'wednesday'],
                    '09:00',
                    '13:00'
                ),
                new OpeningHour(
                    ['monday','tuesday', 'wednesday'],
                    '17:00',
                    '20:00'
                ),
                new OpeningHour(
                    ['friday', 'saturday'],
                    '10:00',
                    '15:00'
                ),
                new OpeningHour(
                    ['friday', 'saturday'],
                    '18:00',
                    '21:00'
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedText('periodWithSplitTimeBlocks'),
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithComplexTimeBlocks(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $place = $place->withOpeningHours(
            [
                new OpeningHour(
                    ['monday','tuesday'],
                    '09:30',
                    '13:45'
                ),
                new OpeningHour(
                    ['monday'],
                    '17:00',
                    '20:00'
                ),
                new OpeningHour(
                    ['tuesday'],
                    '18:00',
                    '20:00'
                ),
                new OpeningHour(
                    ['tuesday'],
                    '21:00',
                    '23:00'
                ),
                new OpeningHour(
                    ['friday', 'saturday'],
                    '10:00',
                    '15:00'
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedText('periodWithComplexTimeBlocks'),
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithoutTimeBlocks(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            'Van dinsdag 25 november 2025 tot en met zaterdag 30 november 2030',
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithoutTimeBlocksWithStatusUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            'Van dinsdag 25 november 2025 tot en met zaterdag 30 november 2030 (geannuleerd)',
            $this->formatter->format($event)
        );
    }
}
