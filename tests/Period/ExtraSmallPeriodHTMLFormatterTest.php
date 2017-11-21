<?php
/**
 * @file
 */

namespace CultuurNet\CalendarSummary\Period;

use \CultureFeed_Cdb_Data_Calendar_Period;
use \CultureFeed_Cdb_Data_Calendar_PeriodList;

class ExtraSmallPeriodHTMLFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExtraSmallPeriodHTMLFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new ExtraSmallPeriodHTMLFormatter();
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
            '<span class="from meta">Vanaf</span> <span class="cf-date">20</span>/<span class="cf-month">3</span>',
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
            '<span class="from meta">Vanaf</span> <span class="cf-date">1</span>/<span class="cf-month">11</span>',
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
            '<span class="from meta">Vanaf</span> <span class="cf-date">11</span>/<span class="cf-month">3</span>',
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
            '<span class="to meta">Tot</span> <span class="cf-date">25</span>/<span class="cf-month">3</span>',
            $this->formatter->format($periodList)
        );
    }
}
