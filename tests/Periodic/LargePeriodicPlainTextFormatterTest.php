<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class LargePeriodicPlainTextFormatterTest extends TestCase
{
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
            'Van 25 november 2025 tot 30 november 2030' . PHP_EOL
            . '(maandag van 0:01 tot 17:00, '
            . 'dinsdag van 0:01 tot 17:00, '
            . 'woensdag van 0:01 tot 17:00, '
            . 'vrijdag van 10:00 tot 18:00, '
            . 'zaterdag van 10:00 tot 18:00)',
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
            'Du 25 novembre 2025 au 30 novembre 2030' . PHP_EOL
            . '(lundi de 0:01 à 17:00, '
            . 'vendredi de 10:00 à 18:00)',
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
            'Van 25 november 2025 tot 30 november 2030' . PHP_EOL
            . '(maandag van 0:01 tot 17:00, '
            . 'dinsdag van 0:01 tot 17:00, '
            . 'woensdag van 0:01 tot 17:00, '
            . 'vrijdag van 10:00 tot 18:00, '
            . 'zaterdag van 10:00 tot 18:00) (geannuleerd)',
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
            'Van 25 november 2025 tot 30 november 2030' . PHP_EOL
            . '(maandag van 9:00 tot 13:00 en van 17:00 tot 20:00, '
            . 'dinsdag van 9:00 tot 13:00 en van 17:00 tot 20:00, '
            . 'woensdag van 9:00 tot 13:00 en van 17:00 tot 20:00, '
            . 'vrijdag van 10:00 tot 15:00 en van 18:00 tot 21:00, '
            . 'zaterdag van 10:00 tot 15:00 en van 18:00 tot 21:00)',
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
            'Van 25 november 2025 tot 30 november 2030' . PHP_EOL
            . '(maandag van 9:30 tot 13:45 en van 17:00 tot 20:00, '
            . 'dinsdag van 9:30 tot 13:45 en van 18:00 tot 20:00 en van 21:00 tot 23:00, '
            . 'vrijdag van 10:00 tot 15:00, '
            . 'zaterdag van 10:00 tot 15:00)',
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
            'Van 25 november 2025 tot 30 november 2030',
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
            'Van 25 november 2025 tot 30 november 2030 (geannuleerd)',
            $this->formatter->format($event)
        );
    }
}
