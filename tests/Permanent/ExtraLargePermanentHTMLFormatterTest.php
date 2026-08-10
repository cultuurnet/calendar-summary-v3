<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use Carbon\CarbonImmutable;
use CultuurNet\CalendarSummaryV3\CalendarSummaryTester;
use CultuurNet\CalendarSummaryV3\Offer\AdjustedDay;
use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
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
            ->withOpeningHoursAdjustedDays([$this->autumnHoliday()]);

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
            . '<details class="cf-exceptions"> '
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
        $place = $this->availablePlace()->withOpeningHoursAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-exceptions"> '
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
        $place = $this->availablePlace()->withOpeningHoursAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            '<p class="cf-openinghours">Ouvert tous les jours</p> '
            . '<details class="cf-exceptions"> '
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
        $place = $this->availablePlace()->withOpeningHoursAdjustedDays(
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
            . '<details class="cf-exceptions"> '
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
        $place = $this->availablePlace()->withOpeningHoursAdjustedDays(
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
            . '<details class="cf-exceptions"> '
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
        $place = $this->availablePlace()->withOpeningHoursAdjustedDays(
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
            . '<details class="cf-exceptions"> '
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
        $place = $this->availablePlace()->withOpeningHoursAdjustedDays(
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
            . '<details class="cf-exceptions"> '
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
        $place = $this->availablePlace()->withOpeningHoursAdjustedDays(
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
            . '<details class="cf-exceptions"> '
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
        $place = $this->availablePlace()->withOpeningHoursAdjustedDays(
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
            . '<details class="cf-exceptions"> '
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
        $place = $this->availablePlace()->withOpeningHoursAdjustedDays(
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
        $place = $this->availablePlace()->withOpeningHoursAdjustedDays(
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
            . '<details class="cf-exceptions"> '
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

    public function testItDoesNotRenderAdjustedDaysForAnUnavailablePermanent(): void
    {
        $event = (new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::permanent()
        ))->withOpeningHoursAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            '<p class="cf-status">Geannuleerd</p>',
            $this->formatter->format($event)
        );
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
}
