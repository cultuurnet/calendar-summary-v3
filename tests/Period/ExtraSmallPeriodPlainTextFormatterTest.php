<?php
/**
 * @file
 */

namespace CultuurNet\CalendarSummary\Period;

use \CultureFeed_Cdb_Data_Calendar_Period;
use \CultureFeed_Cdb_Data_Calendar_PeriodList;

class ExtraSmallPeriodPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExtraSmallPeriodPlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new ExtraSmallPeriodPlainTextFormatter();
    }

    public function testFormatsAPeriod()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2020-03-20',
            '2025-03-27'
        );
        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            'Vanaf 20/3',
            $this->formatter->format($periodList)
        );
    }

    public function testFormatsAPeriodDayWithoutLeadingZero()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2020-11-01',
            '2025-11-05'
        );
        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            'Vanaf 1/11',
            $this->formatter->format($periodList)
        );
    }

    public function testFormatsAPeriodMonthWithoutLeadingZero()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2020-03-11',
            '2025-03-15'
        );
        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            'Vanaf 11/3',
            $this->formatter->format($periodList)
        );
    }

    public function testFormatsAPeriodThatHasAlreadyStarted()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2015-03-19',
            '2020-03-25'
        );
        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            'Tot 25/3',
            $this->formatter->format($periodList)
        );
    }
}
