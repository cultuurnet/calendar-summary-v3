<?php

namespace CultuurNet\CalendarSummary\Timestamps;

class ShowFromTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var showFrom|\PHPUnit_Framework_MockObject_MockObject
     */
    private $showFrom;

    protected function setUp()
    {
        $this->showFrom = $this->getMockForTrait(
            showFrom::class
        );
    }

    public function testItShowFromTimestamp()
    {
        $expectedTimestamp = strtotime('now');

        $this->showFrom->setShowFrom($expectedTimestamp);

        $this->assertEquals(
            $expectedTimestamp,
            $this->showFrom->getShowFrom()
        );
    }

    public function testItHasADefaultTimestampOfTodayMidnight()
    {
        // Theoretically this could fail when run at midnight.
        $expectedTimestamp = strtotime(date('Y-m-d') . ' 00:00:00');

        $timestamp = $this->showFrom->getShowFrom();

        $this->assertEquals(
            $expectedTimestamp,
            $timestamp
        );
    }

    public function testItRequiresAnIntegerTimestamp()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'The timestamp to start showing calendar info from needs to be of type int.'
        );

        $this->showFrom->setShowFrom('now');
    }
}
