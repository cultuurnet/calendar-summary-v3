<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use Carbon\CarbonImmutable;
use CultuurNet\CalendarSummaryV3\CalendarSummaryTester;
use CultuurNet\CalendarSummaryV3\Offer\AdjustedDay;
use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\ClosedDay;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ExtraLargePermanentHTMLFormatterTest extends TestCase
{
    /**
     * @var ExtraLargePermanentHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        // Monday 10 August 2026, so the adjusted days in November 2026 are upcoming.
        CalendarSummaryTester::setTestNow(2026, 8, 10);
        $this->formatter = new ExtraLargePermanentHTMLFormatter(new Translator('nl_NL'));
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();
    }

    public function testFormatASimplePermanent(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday'], '14:00', '20:00'),
                new OpeningHour(['monday', 'tuesday', 'wednesday'], '00:01', '13:00'),
                new OpeningHour(['friday'], '09:00', '13:00'),
                new OpeningHour(['saturday', 'sunday'], '09:00', '19:00'),
            ]
        );

        $this->assertEquals(
            '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 0:01-20:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="0:01" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:01</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span itemprop="opens" content="14:00" class="cf-from cf-meta">en van</span> '
            . '<span class="cf-time">14:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
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

    public function testFormatASharedChildcareAsASingleListItem(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday', 'tuesday'], '09:00', '16:00', new Childcare('08:00', '17:00')),
            ]
        );

        $this->assertEquals(
            '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 9:00-16:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="16:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 9:00-16:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="16:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '</li> '
            . $this->closedDay('Woensdag')
            . $this->closedDay('Donderdag')
            . $this->closedDay('Vrijdag')
            . $this->closedDay('Zaterdag')
            . $this->closedDay('Zondag')
            . '<li class="cf-childcare">Elke dag opvang van 8:00 tot 17:00</li> </ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatADifferingChildcareOnTheDayItself(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday'], '09:00', '16:00', new Childcare('08:00', '17:00')),
                new OpeningHour(['wednesday'], '09:00', '12:00', new Childcare('08:00', '13:00')),
            ]
        );

        $this->assertEquals(
            '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 9:00-16:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="16:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '<span class="cf-childcare">(Opvang van 8:00 tot 17:00)</span> '
            . '</li> '
            . $this->closedDay('Dinsdag')
            . '<meta itemprop="openingHours" datetime="Wo 9:00-12:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Woensdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="12:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">12:00</span> '
            . '<span class="cf-childcare">(Opvang van 8:00 tot 13:00)</span> '
            . '</li> '
            . $this->closedDay('Donderdag')
            . $this->closedDay('Vrijdag')
            . $this->closedDay('Zaterdag')
            . $this->closedDay('Zondag')
            . '</ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatChildcareOnTheDayItselfWhenNotEveryDayHasIt(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday'], '09:00', '16:00', new Childcare('08:00', '17:00')),
                new OpeningHour(['tuesday'], '09:00', '16:00'),
            ]
        );

        $this->assertEquals(
            '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 9:00-16:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="16:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '<span class="cf-childcare">(Opvang van 8:00 tot 17:00)</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 9:00-16:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="16:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '</li> '
            . $this->closedDay('Woensdag')
            . $this->closedDay('Donderdag')
            . $this->closedDay('Vrijdag')
            . $this->closedDay('Zaterdag')
            . $this->closedDay('Zondag')
            . '</ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatTheChildcareOfADayWithMultipleTimespansOnlyOnce(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday'], '09:00', '12:00', new Childcare('08:00', '18:00')),
                new OpeningHour(['monday'], '13:00', '17:00', new Childcare('08:00', '18:00')),
                new OpeningHour(['tuesday'], '09:00', '16:00'),
            ]
        );

        $this->assertEquals(
            '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 9:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="12:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">12:00</span> '
            . '<span itemprop="opens" content="13:00" class="cf-from cf-meta">en van</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span class="cf-childcare">(Opvang van 8:00 tot 18:00)</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 9:00-16:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="16:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '</li> '
            . $this->closedDay('Woensdag')
            . $this->closedDay('Donderdag')
            . $this->closedDay('Vrijdag')
            . $this->closedDay('Zaterdag')
            . $this->closedDay('Zondag')
            . '</ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatTheChildcareOfEveryTimespanWhenItDiffersOnTheSameDay(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday'], '09:00', '12:00', new Childcare('08:00', '13:00')),
                new OpeningHour(['monday'], '13:00', '17:00', new Childcare('12:00', '18:00')),
            ]
        );

        $this->assertEquals(
            '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 9:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="12:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">12:00</span> '
            . '<span class="cf-childcare">(Opvang van 8:00 tot 13:00)</span> '
            . '<span itemprop="opens" content="13:00" class="cf-from cf-meta">en van</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span class="cf-childcare">(Opvang van 12:00 tot 18:00)</span> '
            . '</li> '
            . $this->closedDay('Dinsdag')
            . $this->closedDay('Woensdag')
            . $this->closedDay('Donderdag')
            . $this->closedDay('Vrijdag')
            . $this->closedDay('Zaterdag')
            . $this->closedDay('Zondag')
            . '</ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatASharedChildcareInAnAdjustedDay(): void
    {
        $place = $this->availablePlace()->withAdjustedDays(
            [
                new AdjustedDay(
                    new DateTimeImmutable('2026-11-02'),
                    new DateTimeImmutable('2026-11-07'),
                    [
                        new OpeningHour(['monday', 'tuesday'], '09:00', '12:00', new Childcare('08:00', '18:00')),
                        new OpeningHour(['thursday'], '13:00', '17:00', new Childcare('08:00', '18:00')),
                    ],
                    ['nl' => 'Herfstvakantie']
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Maandag 2 november 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zaterdag 7 november 2026</span> '
            . '<span class="cf-days">Maandag - dinsdag</span> '
            . '<span class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">12:00</span> '
            . '<span class="cf-days">Donderdag</span> '
            . '<span class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span class="cf-childcare">Elke dag opvang van 8:00 tot 18:00</span> '
            . '<span class="cf-description">Herfstvakantie</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testFormatADifferingChildcareInAnAdjustedDay(): void
    {
        $place = $this->availablePlace()->withAdjustedDays(
            [
                new AdjustedDay(
                    new DateTimeImmutable('2026-11-02'),
                    new DateTimeImmutable('2026-11-07'),
                    [
                        new OpeningHour(['monday', 'tuesday'], '09:00', '12:00', new Childcare('08:00', '13:00')),
                        new OpeningHour(['thursday'], '13:00', '17:00', new Childcare('12:00', '18:00')),
                    ]
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Maandag 2 november 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zaterdag 7 november 2026</span> '
            . '<span class="cf-days">Maandag - dinsdag</span> '
            . '<span class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">12:00</span> '
            . '<span class="cf-childcare">(Opvang van 8:00 tot 13:00)</span> '
            . '<span class="cf-days">Donderdag</span> '
            . '<span class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span class="cf-childcare">(Opvang van 12:00 tot 18:00)</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testFormatASharedChildcareInFrench(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday'], '09:00', '16:00', new Childcare('08:00', '17:00')),
            ]
        );

        $this->assertStringContainsString(
            '<li class="cf-childcare">Chaque jour garderie de 8:00 à 17:00</li>',
            (new ExtraLargePermanentHTMLFormatter(new Translator('fr_BE')))->format($place)
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

    public function testFormatPermanentWithoutOpeningHours(): void
    {
        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p>',
            $this->formatter->format($this->availablePlace())
        );
    }

    public function testFormatAdjustedDaysAfterTheWeekScheme(): void
    {
        $place = $this->availablePlace()
            ->withOpeningHours([new OpeningHour(['monday'], '09:00', '13:00')])
            ->withAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 9:00-13:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Dinsdag"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
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
            . '<meta itemprop="openingHours" datetime="Vrijdag"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Vrijdag</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Zaterdag"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zaterdag</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Zondag"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zondag</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            . '</li> </ul> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Maandag 2 november 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zaterdag 7 november 2026</span> '
            . '<span class="cf-days">Maandag - donderdag</span> '
            . '<span class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '<span class="cf-description">Herfstvakantie</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAdjustedDaysForASinglePeriod(): void
    {
        $place = $this->availablePlace()->withAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Maandag 2 november 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zaterdag 7 november 2026</span> '
            . '<span class="cf-days">Maandag - donderdag</span> '
            . '<span class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '<span class="cf-description">Herfstvakantie</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAdjustedDaysForASinglePeriodInFrench(): void
    {
        $place = $this->availablePlace()->withAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            '<p class="cf-openinghours">Ouvert tous les jours</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Sauf pendant</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Lundi 2 novembre 2026</span> '
            . '<span class="cf-to cf-meta">au</span> '
            . '<span class="cf-date">samedi 7 novembre 2026</span> '
            . '<span class="cf-days">Lundi - jeudi</span> '
            . '<span class="cf-from cf-meta">de</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span class="cf-to cf-meta">à</span> '
            . '<span class="cf-time">16:00</span> '
            . '</li> </ul> </details>',
            (new ExtraLargePermanentHTMLFormatter(new Translator('fr_BE')))->format($place)
        );
    }

    public function testFormatAdjustedDaysForASingleDay(): void
    {
        $place = $this->availablePlace()->withAdjustedDays(
            [
                new AdjustedDay(
                    new DateTimeImmutable('2026-11-02'),
                    new DateTimeImmutable('2026-11-02'),
                    [new OpeningHour(['monday'], '10:00', '12:00')],
                    ['nl' => 'Feestdag']
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Maandag 2 november 2026</span> '
            . '<span class="cf-days">Maandag</span> '
            . '<span class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">12:00</span> '
            . '<span class="cf-description">Feestdag</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAdjustedDaysWithMultipleOpeningHours(): void
    {
        $place = $this->availablePlace()->withAdjustedDays(
            [
                new AdjustedDay(
                    new DateTimeImmutable('2026-11-02'),
                    new DateTimeImmutable('2026-11-07'),
                    [
                        new OpeningHour(['monday', 'tuesday'], '09:00', '12:00'),
                        new OpeningHour(['thursday', 'friday', 'saturday'], '13:00', '17:00'),
                    ],
                    ['nl' => 'Herfstvakantie']
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Maandag 2 november 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zaterdag 7 november 2026</span> '
            . '<span class="cf-days">Maandag - dinsdag</span> '
            . '<span class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">12:00</span> '
            . '<span class="cf-days">Donderdag - zaterdag</span> '
            . '<span class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span class="cf-description">Herfstvakantie</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAdjustedDaysWithNonConsecutiveDays(): void
    {
        $place = $this->availablePlace()->withAdjustedDays(
            [
                new AdjustedDay(
                    new DateTimeImmutable('2026-11-02'),
                    new DateTimeImmutable('2026-11-07'),
                    [new OpeningHour(['wednesday', 'monday'], '09:00', '16:00')]
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Maandag 2 november 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zaterdag 7 november 2026</span> '
            . '<span class="cf-days">Maandag, woensdag</span> '
            . '<span class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAdjustedDaysWithoutOpeningHours(): void
    {
        $place = $this->availablePlace()->withAdjustedDays(
            [
                new AdjustedDay(
                    new DateTimeImmutable('2026-12-24'),
                    new DateTimeImmutable('2027-01-03'),
                    [],
                    ['nl' => 'Kerstvakantie']
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Donderdag 24 december 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zondag 3 januari 2027</span> '
            . '<span class="cf-description">Kerstvakantie</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testItDoesNotRenderTheDescriptionWhenTranslationIsUnavailable(): void
    {
        $place = $this->availablePlace()->withAdjustedDays(
            [
                new AdjustedDay(
                    new DateTimeImmutable('2026-11-02'),
                    new DateTimeImmutable('2026-11-02'),
                    [],
                    ['fr' => 'Congé d\'automne']
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Maandag 2 november 2026</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testItRendersMultipleAdjustedDays(): void
    {
        $place = $this->availablePlace()->withAdjustedDays(
            [
                new AdjustedDay(
                    new DateTimeImmutable('2026-11-02'),
                    new DateTimeImmutable('2026-11-02'),
                    [],
                    ['nl' => 'Herfstvakantie']
                ),
                new AdjustedDay(
                    new DateTimeImmutable('2026-12-24'),
                    new DateTimeImmutable('2026-12-24'),
                    [],
                    ['nl' => 'Kerstavond']
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Maandag 2 november 2026</span> '
            . '<span class="cf-description">Herfstvakantie</span> '
            . '</li> '
            . '<li> '
            . '<span class="cf-date">Donderdag 24 december 2026</span> '
            . '<span class="cf-description">Kerstavond</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testItSkipsAdjustedDaysThatHavePassed(): void
    {
        $place = $this->availablePlace()->withAdjustedDays(
            [
                new AdjustedDay(
                    new DateTimeImmutable('2026-08-03'),
                    new DateTimeImmutable('2026-08-09'),
                    [],
                    ['nl' => 'Voorbij']
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p>',
            $this->formatter->format($place)
        );
    }

    public function testItRendersAdjustedDaysThatEndToday(): void
    {
        $place = $this->availablePlace()->withAdjustedDays(
            [
                new AdjustedDay(
                    new DateTimeImmutable('2026-08-03'),
                    new DateTimeImmutable('2026-08-10'),
                    [],
                    ['nl' => 'Loopt vandaag af']
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Maandag 3 augustus 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">maandag 10 augustus 2026</span> '
            . '<span class="cf-description">Loopt vandaag af</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testFormatClosedDays(): void
    {
        $place = $this->availablePlace()->withClosedDays([$this->christmasHoliday()]);

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-closed-days"> '
            . '<summary>Gesloten</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Donderdag 24 december 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zondag 3 januari 2027</span> '
            . '<span class="cf-description">Kerstvakantie</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testFormatClosedDaysInFrench(): void
    {
        $place = $this->availablePlace()->withClosedDays([$this->christmasHoliday()]);

        $this->assertEquals(
            '<p class="cf-openinghours">Ouvert tous les jours</p> '
            . '<details class="cf-closed-days"> '
            . '<summary>Fermé</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Jeudi 24 décembre 2026</span> '
            . '<span class="cf-to cf-meta">au</span> '
            . '<span class="cf-date">dimanche 3 janvier 2027</span> '
            . '</li> </ul> </details>',
            (new ExtraLargePermanentHTMLFormatter(new Translator('fr_BE')))->format($place)
        );
    }

    public function testFormatClosedDaysForASingleDay(): void
    {
        $place = $this->availablePlace()->withClosedDays(
            [
                new ClosedDay(
                    new DateTimeImmutable('2026-12-25'),
                    new DateTimeImmutable('2026-12-25'),
                    ['nl' => 'Kerstmis']
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-closed-days"> '
            . '<summary>Gesloten</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Vrijdag 25 december 2026</span> '
            . '<span class="cf-description">Kerstmis</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testItRendersMultipleClosedDays(): void
    {
        $place = $this->availablePlace()->withClosedDays(
            [
                $this->christmasHoliday(),
                new ClosedDay(
                    new DateTimeImmutable('2027-04-05'),
                    new DateTimeImmutable('2027-04-18'),
                    ['nl' => 'Paasvakantie']
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-closed-days"> '
            . '<summary>Gesloten</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Donderdag 24 december 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zondag 3 januari 2027</span> '
            . '<span class="cf-description">Kerstvakantie</span> '
            . '</li> '
            . '<li> '
            . '<span class="cf-date">Maandag 5 april 2027</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zondag 18 april 2027</span> '
            . '<span class="cf-description">Paasvakantie</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testItSkipsClosedDaysThatHavePassed(): void
    {
        $place = $this->availablePlace()->withClosedDays(
            [
                new ClosedDay(
                    new DateTimeImmutable('2026-08-03'),
                    new DateTimeImmutable('2026-08-09'),
                    ['nl' => 'Voorbij']
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p>',
            $this->formatter->format($place)
        );
    }

    public function testItRendersTheClosedDaysAfterTheAdjustedDays(): void
    {
        $place = $this->availablePlace()
            ->withAdjustedDays([$this->autumnHoliday()])
            ->withClosedDays([$this->christmasHoliday()]);

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Maandag 2 november 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zaterdag 7 november 2026</span> '
            . '<span class="cf-days">Maandag - donderdag</span> '
            . '<span class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '<span class="cf-description">Herfstvakantie</span> '
            . '</li> </ul> </details> '
            . '<details class="cf-closed-days"> '
            . '<summary>Gesloten</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Donderdag 24 december 2026</span> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-date">zondag 3 januari 2027</span> '
            . '<span class="cf-description">Kerstvakantie</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place)
        );
    }

    public function testItDoesNotRenderClosedDaysForAnUnavailablePermanent(): void
    {
        $event = (new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::permanent()
        ))->withClosedDays([$this->christmasHoliday()]);

        $this->assertEquals(
            '<p class="cf-status">Geannuleerd</p>',
            $this->formatter->format($event)
        );
    }

    public function testItDoesNotRenderAdjustedDaysForAnUnavailablePermanent(): void
    {
        $event = (new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::permanent()
        ))->withAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            '<p class="cf-status">Geannuleerd</p>',
            $this->formatter->format($event)
        );
    }

    private function closedDay(string $translatedDay): string
    {
        return '<meta itemprop="openingHours" datetime="' . $translatedDay . '"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">' . $translatedDay . '</span> '
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            . '</li> ';
    }

    private function availablePlace(): Offer
    {
        return new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::permanent()
        );
    }

    private function autumnHoliday(): AdjustedDay
    {
        return new AdjustedDay(
            new DateTimeImmutable('2026-11-02'),
            new DateTimeImmutable('2026-11-07'),
            [new OpeningHour(['monday', 'tuesday', 'wednesday', 'thursday'], '09:00', '16:00')],
            ['nl' => 'Herfstvakantie']
        );
    }

    private function christmasHoliday(): ClosedDay
    {
        return new ClosedDay(
            new DateTimeImmutable('2026-12-24'),
            new DateTimeImmutable('2027-01-03'),
            ['nl' => 'Kerstvakantie']
        );
    }
}
