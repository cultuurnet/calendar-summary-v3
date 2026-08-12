<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use Carbon\CarbonImmutable;
use CultuurNet\CalendarSummaryV3\CalendarSummaryTester;
use CultuurNet\CalendarSummaryV3\HtmlFixture;
use CultuurNet\CalendarSummaryV3\Offer\AdjustedDay;
use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\ClosedDay;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHours;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class ExtraLargePermanentHTMLFormatterTest extends TestCase
{
    use HtmlFixture;

    protected ExtraLargePermanentHTMLFormatter $formatter;

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
            $this->expectedHtml('simplePermanent'),
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
            $this->expectedHtml('sharedChildcareAsASingleListItem'),
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
            $this->expectedHtml('differingChildcareOnTheDayItself'),
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
            $this->expectedHtml('childcareOnTheDayItselfWhenNotEveryDayHasIt'),
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
            $this->expectedHtml('childcareOfADayWithMultipleTimespansOnlyOnce'),
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
            $this->expectedHtml('childcareOfEveryTimespanWhenItDiffersOnTheSameDay'),
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
                    new OpeningHours([
                        new OpeningHour(['monday', 'tuesday'], '09:00', '12:00', new Childcare('08:00', '18:00')),
                        new OpeningHour(['thursday'], '13:00', '17:00', new Childcare('08:00', '18:00')),
                    ]),
                    ['nl' => 'Herfstvakantie']
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedHtml('sharedChildcareInAnAdjustedDay'),
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
                    new OpeningHours([
                        new OpeningHour(['monday', 'tuesday'], '09:00', '12:00', new Childcare('08:00', '13:00')),
                        new OpeningHour(['thursday'], '13:00', '17:00', new Childcare('12:00', '18:00')),
                    ])
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedHtml('differingChildcareInAnAdjustedDay'),
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
            $this->expectedHtml('adjustedDaysAfterTheWeekScheme'),
            $this->formatter->format($place)
        );
    }

    public function testFormatAdjustedDaysForASinglePeriod(): void
    {
        $place = $this->availablePlace()->withAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            $this->expectedHtml('adjustedDaysForASinglePeriod'),
            $this->formatter->format($place)
        );
    }

    public function testFormatAdjustedDaysForASinglePeriodInFrench(): void
    {
        $place = $this->availablePlace()->withAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            $this->expectedHtml('adjustedDaysForASinglePeriodInFrench'),
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
                    new OpeningHours([new OpeningHour(['monday'], '10:00', '12:00')]),
                    ['nl' => 'Feestdag']
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedHtml('adjustedDaysForASingleDay'),
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
                    new OpeningHours([
                        new OpeningHour(['monday', 'tuesday'], '09:00', '12:00'),
                        new OpeningHour(['thursday', 'friday', 'saturday'], '13:00', '17:00'),
                    ]),
                    ['nl' => 'Herfstvakantie']
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedHtml('adjustedDaysWithMultipleOpeningHours'),
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
                    new OpeningHours([new OpeningHour(['wednesday', 'monday'], '09:00', '16:00')])
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedHtml('adjustedDaysWithNonConsecutiveDays'),
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
                    new OpeningHours(),
                    ['nl' => 'Kerstvakantie']
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedHtml('adjustedDaysWithoutOpeningHours'),
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
                    new OpeningHours(),
                    ['fr' => 'Congé d\'automne']
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedHtml('itDoesNotRenderTheDescriptionWhenTranslationIsUnavailable'),
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
                    new OpeningHours(),
                    ['nl' => 'Herfstvakantie']
                ),
                new AdjustedDay(
                    new DateTimeImmutable('2026-12-24'),
                    new DateTimeImmutable('2026-12-24'),
                    new OpeningHours(),
                    ['nl' => 'Kerstavond']
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedHtml('itRendersMultipleAdjustedDays'),
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
                    new OpeningHours(),
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
                    new OpeningHours(),
                    ['nl' => 'Loopt vandaag af']
                ),
            ]
        );

        $this->assertEquals(
            $this->expectedHtml('itRendersAdjustedDaysThatEndToday'),
            $this->formatter->format($place)
        );
    }

    public function testFormatClosedDays(): void
    {
        $place = $this->availablePlace()->withClosedDays([$this->christmasHoliday()]);

        $this->assertEquals(
            $this->expectedHtml('closedDays'),
            $this->formatter->format($place)
        );
    }

    public function testFormatClosedDaysInFrench(): void
    {
        $place = $this->availablePlace()->withClosedDays([$this->christmasHoliday()]);

        $this->assertEquals(
            $this->expectedHtml('closedDaysInFrench'),
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
            $this->expectedHtml('closedDaysForASingleDay'),
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
            $this->expectedHtml('itRendersMultipleClosedDays'),
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
            $this->expectedHtml('itRendersTheClosedDaysAfterTheAdjustedDays'),
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
            new OpeningHours([new OpeningHour(['monday', 'tuesday', 'wednesday', 'thursday'], '09:00', '16:00')]),
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
