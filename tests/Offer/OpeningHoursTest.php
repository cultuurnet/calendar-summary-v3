<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use PHPUnit\Framework\TestCase;

final class OpeningHoursTest extends TestCase
{
    public function testSharedChildcareReturnsTheChildcareWhenEveryOpeningHourHasTheSameOne(): void
    {
        $openingHours = new OpeningHours(
            [
                new OpeningHour(['monday'], '09:00', '16:00', new Childcare('08:00', '17:00')),
                new OpeningHour(['tuesday'], '10:00', '15:00', new Childcare('08:00', '17:00')),
            ]
        );

        $this->assertEquals(new Childcare('08:00', '17:00'), $openingHours->sharedChildcare());
    }

    public function testSharedChildcareReturnsNullWhenTheChildcareDiffers(): void
    {
        $openingHours = new OpeningHours(
            [
                new OpeningHour(['monday'], '09:00', '16:00', new Childcare('08:00', '17:00')),
                new OpeningHour(['tuesday'], '09:00', '16:00', new Childcare('08:00', '13:00')),
            ]
        );

        $this->assertNull($openingHours->sharedChildcare());
    }

    public function testSharedChildcareReturnsNullWhenAnOpeningHourHasNoChildcare(): void
    {
        $openingHours = new OpeningHours(
            [
                new OpeningHour(['monday'], '09:00', '16:00', new Childcare('08:00', '17:00')),
                new OpeningHour(['tuesday'], '09:00', '16:00'),
            ]
        );

        $this->assertNull($openingHours->sharedChildcare());
    }

    public function testSharedChildcareReturnsNullWithoutOpeningHours(): void
    {
        $this->assertNull((new OpeningHours())->sharedChildcare());
    }

    public function testEarliestAndLatestTimeCombineTheTimespansOfTheSameDays(): void
    {
        $openingHours = new OpeningHours(
            [
                new OpeningHour(['monday'], '13:00', '17:00'),
                new OpeningHour(['monday'], '09:00', '12:00'),
                new OpeningHour(['tuesday'], '08:00', '19:00'),
            ]
        );

        $this->assertEquals('09:00', $openingHours->earliestTimeOn(['monday']));
        $this->assertEquals('17:00', $openingHours->latestTimeOn(['monday']));
    }

    public function testEarliestAndLatestTimeAreEmptyForDaysWithoutOpeningHours(): void
    {
        $openingHours = new OpeningHours([new OpeningHour(['monday'], '09:00', '17:00')]);

        $this->assertEquals('', $openingHours->earliestTimeOn(['sunday']));
        $this->assertEquals('', $openingHours->latestTimeOn(['sunday']));
    }

    public function testSplitPerDayCreatesAnOpeningHourPerDayOfWeek(): void
    {
        $openingHours = new OpeningHours(
            [
                new OpeningHour(['monday', 'tuesday'], '09:00', '17:00', new Childcare('08:00', '18:00')),
            ]
        );

        $this->assertEquals(
            new OpeningHours(
                [
                    new OpeningHour(['monday'], '09:00', '17:00', new Childcare('08:00', '18:00')),
                    new OpeningHour(['tuesday'], '09:00', '17:00', new Childcare('08:00', '18:00')),
                ]
            ),
            $openingHours->splitPerDay()
        );
    }

    public function testSortedByDayAndOpeningTimeOrdersByWeekDayFirst(): void
    {
        $openingHours = new OpeningHours(
            [
                new OpeningHour(['tuesday'], '09:00', '17:00'),
                new OpeningHour(['monday'], '13:00', '17:00'),
                new OpeningHour(['monday'], '09:00', '12:00'),
            ]
        );

        $this->assertEquals(
            new OpeningHours(
                [
                    new OpeningHour(['monday'], '09:00', '12:00'),
                    new OpeningHour(['monday'], '13:00', '17:00'),
                    new OpeningHour(['tuesday'], '09:00', '17:00'),
                ]
            ),
            $openingHours->sortedByDayAndOpeningTime()
        );
    }

    public function testIsEmptyWithoutOpeningHours(): void
    {
        $this->assertTrue((new OpeningHours())->isEmpty());
        $this->assertFalse((new OpeningHours([new OpeningHour(['monday'], '09:00', '17:00')]))->isEmpty());
    }

    public function testCanBeIterated(): void
    {
        $openingHour = new OpeningHour(['monday'], '09:00', '17:00');

        $this->assertEquals([$openingHour], iterator_to_array(new OpeningHours([$openingHour])));
    }

    public function testCanBeCounted(): void
    {
        $openingHours = new OpeningHours(
            [
                new OpeningHour(['monday'], '09:00', '17:00'),
                new OpeningHour(['tuesday'], '09:00', '17:00'),
            ]
        );

        $this->assertCount(2, $openingHours);
        $this->assertCount(0, new OpeningHours());
    }

    public function testCanBeConvertedToAnArray(): void
    {
        $openingHour = new OpeningHour(['monday'], '09:00', '17:00');

        $this->assertEquals([$openingHour], (new OpeningHours([$openingHour]))->toArray());
    }
}
