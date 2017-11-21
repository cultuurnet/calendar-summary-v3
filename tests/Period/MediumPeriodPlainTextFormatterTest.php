<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 20-3-15
 * Time: 15:58
 */

namespace CultuurNet\CalendarSummary\Period;

use \CultureFeed_Cdb_Data_Calendar_Period;
use \CultureFeed_Cdb_Data_Calendar_PeriodList;

class MediumPeriodPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediumPeriodPlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new MediumPeriodPlainTextFormatter();
    }

    public function testFormatsAPeriod()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2015-03-20',
            '2015-03-27'
        );
        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            'Van 20 maart 2015 tot 27 maart 2015',
            $this->formatter->format($periodList)
        );
    }

    public function testFormatsAPeriodDayWithoutLeadingZero()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2015-03-01',
            '2015-03-05'
        );
        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            'Van 1 maart 2015 tot 5 maart 2015',
            $this->formatter->format($periodList)
        );
    }

    public function testFormatsAPeriodWithSameBeginAndEndDate()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2020-09-20',
            '2020-09-20'
        );
        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            'zondag 20 september 2020',
            $this->formatter->format($periodList)
        );
    }
}
