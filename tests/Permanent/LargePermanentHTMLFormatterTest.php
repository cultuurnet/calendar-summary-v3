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

final class LargePermanentHTMLFormatterTest extends TestCase
{
    /**
     * @var LargePermanentHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new LargePermanentHTMLFormatter(new Translator('nl_NL'));
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
            '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 0:01-13:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="0:01" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:01</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 0:01-13:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="0:01" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:01</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Wo 0:01-13:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Woensdag</span> '
            . '<span itemprop="opens" content="0:01" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:01</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Donderdag"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Donderdag</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Vr 9:00-13:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Vrijdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Za 9:00-19:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zaterdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="19:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">19:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Zo 9:00-19:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zondag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="19:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">19:00</span> '
            . '</li> </ul>',
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
            new DateTimeImmutable('25-11-2025')
        );

        $openingHours1 = new OpeningHour(
            ['monday'],
            '00:01',
            '13:00'
        );

        $openingHours2 = new OpeningHour(
            ['friday'],
            '09:00',
            '13:00'
        );

        $place = $place->withOpeningHours(
            [
                $openingHours1,
                $openingHours2,
            ]
        );

        $this->assertEquals(
            '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Lun. 0:01-13:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Lundi</span> '
            . '<span itemprop="opens" content="0:01" class="cf-from cf-meta">de</span> '
            . '<span class="cf-time">0:01</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">à</span> '
            . '<span class="cf-time">13:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Mardi"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Mardi</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">fermé</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Mercredi"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Mercredi</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">fermé</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Jeudi"> </meta> '
            . '<li itemprop="openingHoursSpecification"> <span class="cf-days">Jeudi</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">fermé</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Ven. 9:00-13:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Vendredi</span> <span itemprop="opens" content="9:00" class="cf-from cf-meta">de</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">à</span> '
            . '<span class="cf-time">13:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Samedi"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Samedi</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">fermé</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Dimanche"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dimanche</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">fermé</span> '
            . '</li> </ul>',
            (new LargePermanentHTMLFormatter(new Translator('fr')))->format($place)
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
            '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 9:00-20:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 9:00-20:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Wo 9:00-20:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Woensdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Donderdag"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Donderdag</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Vr 10:00-21:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Vrijdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">15:00</span> '
            . '<span itemprop="opens" content="18:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">18:00</span> '
            . '<span itemprop="closes" content="21:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">21:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Za 10:00-21:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zaterdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">15:00</span> '
            . '<span itemprop="opens" content="18:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">18:00</span> '
            . '<span itemprop="closes" content="21:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">21:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Zondag"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zondag</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            . '</li> </ul>',
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
            '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 9:30-13:45"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="9:30" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:30</span> '
            . '<span itemprop="closes" content="13:45" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:45</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 9:30-13:45"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="9:30" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:30</span> '
            . '<span itemprop="closes" content="13:45" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:45</span> '
            . '<span itemprop="opens" content="18:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">18:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '<span itemprop="opens" content="21:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">21:00</span> '
            . '<span itemprop="closes" content="23:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">23:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Woensdag"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Woensdag</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Donderdag"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Donderdag</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Vr 10:00-15:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Vrijdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">15:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Za 10:00-15:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zaterdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">15:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Zondag"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zondag</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            . '</li> </ul>',
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
}
