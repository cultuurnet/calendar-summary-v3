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

final class MediumPermanentHTMLFormatterTest extends TestCase
{
    /**
     * @var MediumPermanentHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MediumPermanentHTMLFormatter(new Translator('nl_NL'));
    }

    public function testFormatASimplePermanent(): void
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
            '00:01',
            '13:00'
        );

        $openingHours2 = new OpeningHour(
            ['friday'],
            '09:00',
            '13:00'
        );

        $openingHours3 = new OpeningHour(
            ['saturday', 'sunday'],
            '09:00',
            '19:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours1,
                $openingHours2,
                $openingHours3,
            ]
        );

        $this->assertEquals(
            '<span>Open op <span class="cf-weekdays">'
            . '<span class="cf-weekday-open">ma - wo</span> & '
            . '<span class="cf-weekday-open">vr - zo</span>'
            . '</span></span>',
            $this->formatter->format($place)
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
            '19:00'
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
            '<span>Open op <span class="cf-weekdays">'
            . '<span class="cf-weekday-open">ma - wo</span> & '
            . '<span class="cf-weekday-open">vr - za</span>'
            . '</span></span>',
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
            '<span>Open op <span class="cf-weekdays">'
            . '<span class="cf-weekday-open">ma - di</span> & '
            . '<span class="cf-weekday-open">vr - za</span>'
            . '</span></span>',
            $this->formatter->format($place)
        );
    }

    public function testFormatPermanentClosedOnMondays(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('25-11-2025')
        );

        $openingHours1 = new OpeningHour(
            ['wednesday', 'thursday'],
            '18:00',
            '20:00'
        );

        $openingHours2 = new OpeningHour(
            ['friday'],
            '21:00',
            '23:00'
        );

        $openingHours3 = new OpeningHour(
            ['sunday'],
            '10:00',
            '15:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours1,
                $openingHours2,
                $openingHours3,
            ]
        );

        $this->assertEquals(
            '<span>Open op <span class="cf-weekdays">' .
            '<span class="cf-weekday-open">wo - vr</span> & ' .
            '<span class="cf-weekday-open">zo</span></span></span>',
            $this->formatter->format($place)
        );
    }

    public function testFormatPermanentOpenSevenDayAWeek(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('25-11-2025')
        );

        $openingHours1 = new OpeningHour(
            ['wednesday', 'thursday'],
            '18:00',
            '20:00'
        );

        $openingHours2 = new OpeningHour(
            ['monday', 'tuesday', 'friday'],
            '21:00',
            '23:00'
        );

        $openingHours3 = new OpeningHour(
            ['saturday', 'sunday'],
            '10:00',
            '15:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours1,
                $openingHours2,
                $openingHours3,
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Elke dag open</p>',
            $this->formatter->format($place)
        );
    }

    public function testFormatPermanentOpenOneDayAWeek(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('25-11-2025')
        );

        $openingHours1 = new OpeningHour(
            ['wednesday'],
            '09:00',
            '11:00'
        );

        $openingHours2 = new OpeningHour(
            ['wednesday'],
            '18:00',
            '20:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours1,
                $openingHours2,
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Elke woensdag open</p>',
            $this->formatter->format($place)
        );
    }

    public function testFormatPermanentThreePeriods(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('25-11-2025')
        );

        $openingHours1 = new OpeningHour(
            ['monday', 'tuesday'],
            '18:00',
            '20:00'
        );

        $openingHours2 = new OpeningHour(
            ['thursday', 'friday'],
            '21:00',
            '23:00'
        );

        $openingHours3 = new OpeningHour(
            ['sunday'],
            '10:00',
            '15:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours1,
                $openingHours2,
                $openingHours3,
            ]
        );

        $this->assertEquals(
            '<span>Open op <span class="cf-weekdays">' .
            '<span class="cf-weekday-open">ma - di</span> & ' .
            '<span class="cf-weekday-open">do - vr</span> & ' .
            '<span class="cf-weekday-open">zo</span></span></span>',
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
            '<p class="cf-status">Geannuleerd</p>',
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
            '<p class="cf-status">Uitgesteld</p>',
            $this->formatter->format($event)
        );
    }

    public function testItRendersReasonAsTitleAttribute(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', ['nl' => 'Covid-19']),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::permanent()
        );

        $this->assertEquals(
            '<p title="Covid-19" class="cf-status">Geannuleerd</p>',
            $this->formatter->format($event)
        );
    }

    public function testItDoesNotRendersReasonWhenTranslationIsUnavailable(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', ['fr' => 'Sacre bleu']),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::permanent()
        );

        $this->assertEquals(
            '<p class="cf-status">Geannuleerd</p>',
            $this->formatter->format($event)
        );
    }

    public function testFormatPermanentThreePeriodsMixedSorting(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('25-11-2025')
        );

        $openingHours1 = new OpeningHour(
            ['monday', 'tuesday'],
            '18:00',
            '20:00'
        );

        $openingHours2 = new OpeningHour(
            ['friday', 'saturday'],
            '21:00',
            '23:00'
        );

        $openingHours3 = new OpeningHour(
            ['wednesday'],
            '10:00',
            '15:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours1,
                $openingHours2,
                $openingHours3,
            ]
        );

        $this->assertEquals(
            '<span>Open op <span class="cf-weekdays">' .
            '<span class="cf-weekday-open">ma - wo</span> & ' .
            '<span class="cf-weekday-open">vr - za</span>' .
            '</span></span>',
            $this->formatter->format($place)
        );
    }

    public function testFormatPermanentBreakTheWeek(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('25-11-2025')
        );

        $openingHours1 = new OpeningHour(
            ['monday', 'tuesday'],
            '18:00',
            '20:00'
        );

        $openingHours2 = new OpeningHour(
            ['tuesday', 'saturday'],
            '21:00',
            '23:00'
        );

        $openingHours3 = new OpeningHour(
            ['sunday', 'monday'],
            '10:00',
            '15:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours1,
                $openingHours2,
                $openingHours3,
            ]
        );

        $this->assertEquals(
            '<span>Open op <span class="cf-weekdays">' .
            '<span class="cf-weekday-open">ma - di</span> & ' .
            '<span class="cf-weekday-open">za - zo</span>' .
            '</span></span>',
            $this->formatter->format($place)
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
            '<p class="cf-openinghours">Elke dag open</p>',
            $this->formatter->format($event)
        );
    }
}
