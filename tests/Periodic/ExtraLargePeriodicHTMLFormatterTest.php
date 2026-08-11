<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

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

final class ExtraLargePeriodicHTMLFormatterTest extends TestCase
{
    /**
     * @var ExtraLargePeriodicHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        // Monday 10 August 2026, so the adjusted days in November 2026 are upcoming.
        CalendarSummaryTester::setTestNow(2026, 8, 10);
        $this->formatter = new ExtraLargePeriodicHTMLFormatter(new Translator('nl_NL'));
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();
    }

    public function testFormatAPeriodWithSingleTimeBlocks(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday', 'tuesday', 'wednesday'], '00:00', '17:00'),
                new OpeningHour(['friday', 'saturday'], '10:00', '18:00'),
            ]
        );

        $this->assertEquals(
            '<p class="cf-period"> '
            . '<span class="cf-weekday cf-meta">dinsdag</span> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-weekday cf-meta">zaterdag</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p> '
            . '<p class="cf-openinghours">Open op:</p> '
            . '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 0:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 0:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Wo 0:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Woensdag</span> '
            . '<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Vr 10:00-18:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Vrijdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="18:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">18:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Za 10:00-18:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zaterdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="18:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">18:00</span> '
            . '</li> </ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithoutTimeBlocks(): void
    {
        $this->assertEquals(
            '<p class="cf-period"> '
            . '<span class="cf-weekday cf-meta">dinsdag</span> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-weekday cf-meta">zaterdag</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p>',
            $this->formatter->format($this->availablePlace())
        );
    }

    public function testFormatAPeriodWithSingleTimeBlocksWithUnavailableStatus(): void
    {
        $event = (new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        ))->withOpeningHours([new OpeningHour(['monday'], '00:00', '17:00')]);

        $this->assertEquals(
            '<p class="cf-period"> '
            . '<span class="cf-weekday cf-meta">dinsdag</span> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-weekday cf-meta">zaterdag</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '<span class="cf-status">(geannuleerd)</span> '
            . '</p> '
            . '<p class="cf-openinghours">Open op:</p> '
            . '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 0:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> </ul>',
            $this->formatter->format($event)
        );
    }

    public function testFormatAPeriodWithAdjustedDays(): void
    {
        $place = $this->availablePlace()
            ->withOpeningHours([new OpeningHour(['monday'], '08:00', '17:00')])
            ->withAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            '<p class="cf-period"> '
            . '<span class="cf-weekday cf-meta">dinsdag</span> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-weekday cf-meta">zaterdag</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p> '
            . '<p class="cf-openinghours">Open op:</p> '
            . '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 8:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="8:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">8:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
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

    public function testFormatAPeriodWithAdjustedDaysButWithoutTimeBlocks(): void
    {
        $place = $this->availablePlace()->withAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            '<p class="cf-period"> '
            . '<span class="cf-weekday cf-meta">dinsdag</span> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-weekday cf-meta">zaterdag</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p> '
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
            '<p class="cf-period"> '
            . '<span class="cf-weekday cf-meta">dinsdag</span> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-weekday cf-meta">zaterdag</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithClosedDays(): void
    {
        $place = $this->availablePlace()
            ->withOpeningHours([new OpeningHour(['monday'], '08:00', '17:00')])
            ->withClosedDays(
                [
                    new ClosedDay(
                        new DateTimeImmutable('2026-12-24'),
                        new DateTimeImmutable('2027-01-03'),
                        ['nl' => 'Kerstvakantie']
                    ),
                ]
            );

        $this->assertEquals(
            '<p class="cf-period"> '
            . '<span class="cf-weekday cf-meta">dinsdag</span> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-weekday cf-meta">zaterdag</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p> '
            . '<p class="cf-openinghours">Open op:</p> '
            . '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 8:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="8:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">8:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> </ul> '
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
            '<p class="cf-period"> '
            . '<span class="cf-weekday cf-meta">dinsdag</span> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-weekday cf-meta">zaterdag</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p>',
            $this->formatter->format($place)
        );
    }

    public function testFormatASharedChildcareAsASingleListItem(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday', 'tuesday'], '09:00', '16:00', new Childcare('08:00', '18:00')),
            ]
        );

        $this->assertEquals(
            $this->period()
            . '<p class="cf-openinghours">Open op:</p> '
            . '<ul class="list-unstyled"> '
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
            . '<li class="cf-childcare">Elke dag opvang van 8:00 tot 18:00</li> </ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatADifferingChildcareOnTheDayItself(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday'], '09:00', '16:00', new Childcare('08:00', '17:00')),
                new OpeningHour(['tuesday'], '09:00', '12:00', new Childcare('08:00', '13:00')),
            ]
        );

        $this->assertEquals(
            $this->period()
            . '<p class="cf-openinghours">Open op:</p> '
            . '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 9:00-16:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="16:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '<span class="cf-childcare">(Opvang van 8:00 tot 17:00)</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 9:00-12:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="12:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">12:00</span> '
            . '<span class="cf-childcare">(Opvang van 8:00 tot 13:00)</span> '
            . '</li> </ul>',
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

        $summary = $this->formatter->format($place);

        $this->assertStringContainsString('<span class="cf-childcare">(Opvang van 8:00 tot 17:00)</span>', $summary);
        $this->assertStringNotContainsString('cf-childcare">Elke dag', $summary);
    }

    public function testFormatASharedChildcareWithoutAnEnd(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday', 'tuesday'], '10:00', '16:00', new Childcare('09:00', null)),
            ]
        );

        $this->assertStringContainsString(
            '<li class="cf-childcare">Elke dag vooropvang vanaf 9:00</li>',
            $this->formatter->format($place)
        );
    }

    public function testFormatASharedChildcareWithoutAStart(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday', 'tuesday'], '10:00', '16:00', new Childcare(null, '18:00')),
            ]
        );

        $this->assertStringContainsString(
            '<li class="cf-childcare">Elke dag naopvang tot 18:00</li>',
            $this->formatter->format($place)
        );
    }

    public function testFormatADifferingChildcareWithoutAnEndOrWithoutAStart(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday'], '10:00', '16:00', new Childcare('09:00', null)),
                new OpeningHour(['tuesday'], '10:00', '16:00', new Childcare(null, '18:00')),
            ]
        );

        $this->assertEquals(
            $this->period()
            . '<p class="cf-openinghours">Open op:</p> '
            . '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 10:00-16:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="16:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '<span class="cf-childcare">(Vooropvang vanaf 9:00)</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 10:00-16:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="16:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">16:00</span> '
            . '<span class="cf-childcare">(Naopvang tot 18:00)</span> '
            . '</li> </ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAChildcareWithoutAnEndInFrench(): void
    {
        $place = $this->availablePlace()->withOpeningHours(
            [
                new OpeningHour(['monday'], '10:00', '16:00', new Childcare('09:00', null)),
            ]
        );

        $this->assertStringContainsString(
            '<li class="cf-childcare">Chaque jour garderie du matin dès 9:00</li>',
            (new ExtraLargePeriodicHTMLFormatter(new Translator('fr_BE')))->format($place)
        );
    }

    private function period(): string
    {
        return '<p class="cf-period"> '
            . '<span class="cf-weekday cf-meta">dinsdag</span> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot en met</span> '
            . '<span class="cf-weekday cf-meta">zaterdag</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p> ';
    }

    private function availablePlace(): Offer
    {
        return new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
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
