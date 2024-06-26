<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class LargePermanentPlainTextFormatterTest extends TestCase
{
    /**
     * @var LargePermanentPlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new LargePermanentPlainTextFormatter(new Translator('nl_NL'));
    }

    public function testFormatASimplePermanent(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030')
        );

        $openingHours1 = new OpeningHour(
            ['monday','tuesday', 'wednesday'],
            '09:00',
            '13:00'
        );

        $openingHours2 = new OpeningHour(
            ['monday'],
            '14:00',
            '20:00'
        );

        $openingHours3 = new OpeningHour(
            ['friday'],
            '00:01',
            '13:00'
        );

        $openingHours4 = new OpeningHour(
            ['saturday', 'sunday'],
            '09:00',
            '19:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours2,
                $openingHours1,
                $openingHours3,
                $openingHours4,
            ]
        );

        $this->assertEquals(
            'Maandag van 9:00 tot 13:00 en van 14:00 tot 20:00' . PHP_EOL
            . 'Dinsdag van 9:00 tot 13:00' . PHP_EOL
            . 'Woensdag van 9:00 tot 13:00' . PHP_EOL
            . 'Donderdag gesloten' . PHP_EOL
            . 'Vrijdag van 0:01 tot 13:00' . PHP_EOL
            . 'Zaterdag van 9:00 tot 19:00' . PHP_EOL
            . 'Zondag van 9:00 tot 19:00' . PHP_EOL,
            $this->formatter->format($place)
        );
    }

    public function testFormatASimplePermanentInFrench(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030')
        );

        $openingHours1 = new OpeningHour(
            ['monday'],
            '09:00',
            '13:00'
        );

        $openingHours2 = new OpeningHour(
            ['friday'],
            '00:01',
            '13:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours1,
                $openingHours2,
            ]
        );

        $this->assertEquals(
            'Lundi de 9:00 à 13:00' . PHP_EOL
            . 'Mardi fermé' . PHP_EOL
            . 'Mercredi fermé' . PHP_EOL
            . 'Jeudi fermé' . PHP_EOL
            . 'Vendredi de 0:01 à 13:00' . PHP_EOL
            . 'Samedi fermé' . PHP_EOL
            . 'Dimanche fermé' . PHP_EOL,
            (new LargePermanentPlainTextFormatter(new Translator('fr')))->format($place)
        );
    }

    public function testFormatAMixedPermanent(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('25-11-2025')
        );

        $openingHours1 = new OpeningHour(
            ['monday','tuesday', 'wednesday'],
            '09:00',
            '13:00'
        );

        $openingHours2 = new OpeningHour(
            ['monday','tuesday', 'wednesday'],
            '17:00',
            '20:00'
        );

        $openingHours3 = new OpeningHour(
            ['friday', 'saturday'],
            '10:00',
            '15:00'
        );

        $openingHours4 = new OpeningHour(
            ['friday', 'saturday'],
            '18:00',
            '21:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours1,
                $openingHours2,
                $openingHours3,
                $openingHours4,
            ]
        );

        $this->assertEquals(
            'Maandag van 9:00 tot 13:00 en van 17:00 tot 20:00' . PHP_EOL
            . 'Dinsdag van 9:00 tot 13:00 en van 17:00 tot 20:00' . PHP_EOL
            . 'Woensdag van 9:00 tot 13:00 en van 17:00 tot 20:00' . PHP_EOL
            . 'Donderdag gesloten' . PHP_EOL
            . 'Vrijdag van 10:00 tot 15:00 en van 18:00 tot 21:00' . PHP_EOL
            . 'Zaterdag van 10:00 tot 15:00 en van 18:00 tot 21:00' . PHP_EOL
            . 'Zondag gesloten' . PHP_EOL,
            $this->formatter->format($place)
        );
    }

    public function testFormatAComplexPermanent(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('25-11-2025')
        );

        $openingHours1 = new OpeningHour(
            ['monday','tuesday'],
            '09:30',
            '13:45'
        );

        $openingHours2 = new OpeningHour(
            ['monday'],
            '17:00',
            '20:00'
        );

        $openingHours3 = new OpeningHour(
            ['tuesday'],
            '18:00',
            '20:00'
        );

        $openingHours4 = new OpeningHour(
            ['tuesday'],
            '21:00',
            '23:00'
        );

        $openingHours5 = new OpeningHour(
            ['friday', 'saturday'],
            '10:00',
            '15:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours1,
                $openingHours2,
                $openingHours3,
                $openingHours4,
                $openingHours5,
            ]
        );

        $this->assertEquals(
            'Maandag van 9:30 tot 13:45 en van 17:00 tot 20:00' . PHP_EOL
            . 'Dinsdag van 9:30 tot 13:45 en van 18:00 tot 20:00 en van 21:00 tot 23:00' . PHP_EOL
            . 'Woensdag gesloten' . PHP_EOL
            . 'Donderdag gesloten' . PHP_EOL
            . 'Vrijdag van 10:00 tot 15:00' . PHP_EOL
            . 'Zaterdag van 10:00 tot 15:00' . PHP_EOL
            . 'Zondag gesloten' . PHP_EOL,
            $this->formatter->format($place)
        );
    }

    public function testFormatAnUnavailablePermanent(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::permanent()
        );

        $this->assertEquals(
            'Geannuleerd',
            $this->formatter->format($event)
        );
    }

    public function testFormatATemporarilyUnavailablePermanent(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('TemporarilyUnavailable', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::permanent()
        );

        $this->assertEquals(
            'Uitgesteld',
            $this->formatter->format($event)
        );
    }

    public function testFormatPermanentWithoutOpeningHours(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::permanent()
        );

        $this->assertEquals(
            'Alle dagen open' . PHP_EOL,
            $this->formatter->format($event)
        );
    }
}
