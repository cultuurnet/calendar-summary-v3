<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

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

final class ExtraLargePeriodicHTMLFormatterTest extends TestCase
{
    use HtmlFixture;

    protected ExtraLargePeriodicHTMLFormatter $formatter;

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
            $this->expectedHtml('periodWithSingleTimeBlocks'),
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithoutTimeBlocks(): void
    {
        $this->assertEquals(
            $this->expectedHtml('periodWithoutTimeBlocks'),
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
            $this->expectedHtml('periodWithSingleTimeBlocksWithUnavailableStatus'),
            $this->formatter->format($event)
        );
    }

    public function testFormatAPeriodWithAdjustedDays(): void
    {
        $place = $this->availablePlace()
            ->withOpeningHours([new OpeningHour(['monday'], '08:00', '17:00')])
            ->withAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            $this->expectedHtml('periodWithAdjustedDays'),
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithAdjustedDaysButWithoutTimeBlocks(): void
    {
        $place = $this->availablePlace()->withAdjustedDays([$this->autumnHoliday()]);

        $this->assertEquals(
            $this->expectedHtml('periodWithAdjustedDaysButWithoutTimeBlocks'),
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
            $this->expectedHtml('itSkipsAdjustedDaysThatHavePassed'),
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
            $this->expectedHtml('periodWithClosedDays'),
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
            $this->expectedHtml('itSkipsClosedDaysThatHavePassed'),
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
            $this->expectedHtml('sharedChildcareAsASingleListItem'),
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

        $summary = $this->formatter->format($place);

        $this->assertStringContainsString('<span class="cf-childcare">(Opvang van 8:00 tot 17:00)</span>', $summary);
        $this->assertStringNotContainsString('cf-childcare">Elke dag', $summary);
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
            $this->expectedHtml('differingChildcareWithoutAnEndOrWithoutAStart'),
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
            new OpeningHours([new OpeningHour(['monday', 'tuesday', 'wednesday', 'thursday'], '09:00', '16:00')]),
            ['nl' => 'Herfstvakantie']
        );
    }
}
