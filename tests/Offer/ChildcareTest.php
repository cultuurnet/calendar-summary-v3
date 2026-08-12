<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class ChildcareTest extends TestCase
{
    public function testCanHaveNoEnd(): void
    {
        $childcare = new Childcare('09:00', null);

        $this->assertEquals('09:00', $childcare->getStart());
        $this->assertNull($childcare->getEnd());
    }

    public function testCanHaveNoStart(): void
    {
        $childcare = new Childcare(null, '18:00');

        $this->assertNull($childcare->getStart());
        $this->assertEquals('18:00', $childcare->getEnd());
    }

    public function testCanNotHaveNeitherAStartNorAnEnd(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Childcare(null, null);
    }

    public function testAChildcareWithoutEndDiffersFromOneWithoutStart(): void
    {
        $this->assertFalse((new Childcare('09:00', null))->equals(new Childcare(null, '09:00')));
    }

    public function testIsAbsentWhenTheOpeningHourHasAnEmptyChildcare(): void
    {
        $openingHour = OpeningHour::fromArray(
            [
                'dayOfWeek' => ['monday'],
                'opens' => '09:00',
                'closes' => '16:00',
                'childcare' => [],
            ]
        );

        $this->assertNull($openingHour->getChildcare());
    }

    public function testIsParsedWhenTheOpeningHourOnlyHasAChildcareStart(): void
    {
        $openingHour = OpeningHour::fromArray(
            [
                'dayOfWeek' => ['monday'],
                'opens' => '09:00',
                'closes' => '16:00',
                'childcare' => ['start' => '08:00'],
            ]
        );

        $this->assertEquals(new Childcare('08:00', null), $openingHour->getChildcare());
    }
}
