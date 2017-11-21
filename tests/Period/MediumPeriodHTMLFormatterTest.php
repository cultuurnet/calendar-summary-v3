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

class MediumPeriodHTMLFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediumPeriodHTMLFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new MediumPeriodHTMLFormatter();
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
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">20 maart 2015</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">27 maart 2015</span>',
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
            '<span class="cf-from cf-meta">Van</span> <span class="cf-date">1 maart 2015</span> '
            . '<span class="cf-to cf-meta">tot</span> <span class="cf-date">5 maart 2015</span>',
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

        $output = '<span class="cf-weekday cf-meta">zondag</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">20 september 2020</span>';

        $this->assertEquals(
            $output,
            $this->formatter->format($periodList)
        );
    }
}
