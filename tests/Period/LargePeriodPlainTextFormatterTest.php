<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 24-3-15
 * Time: 14:52
 */

namespace CultuurNet\CalendarSummary\Period;

use \CultureFeed_Cdb_Data_Calendar_Period;
use \CultureFeed_Cdb_Data_Calendar_PeriodList;
use \CultureFeed_Cdb_Data_Calendar_SchemeDay as SchemeDay;

class LargePeriodPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargePeriodPlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new LargePeriodPlainTextFormatter();
    }

    public function testFormatsAPeriod()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2015-03-20',
            '2015-03-27'
        );
        $weekscheme=new \CultureFeed_Cdb_Data_Calendar_Weekscheme();

        $monday=new \CultureFeed_Cdb_Data_Calendar_SchemeDay(SchemeDay::MONDAY, SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN);
        $ot1 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $ot2 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('17:00:00', '20:00:00');
        $monday->addOpeningTime($ot1);
        $monday->addOpeningTime($ot2);

        $tuesday=new \CultureFeed_Cdb_Data_Calendar_SchemeDay(SchemeDay::TUESDAY, SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN);
        $ot3 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $ot4= new \CultureFeed_Cdb_Data_Calendar_OpeningTime('17:00:00', '20:00:00');
        $tuesday->addOpeningTime($ot3);
        $tuesday->addOpeningTime($ot4);

        $wednesday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::WEDNESDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot5 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '17:00:00');
        $wednesday->addOpeningTime($ot5);

        $friday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(SchemeDay::FRIDAY, SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN);
        $ot6 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $ot7 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('17:00:00', '20:00:00');
        $friday->addOpeningTime($ot6);
        $friday->addOpeningTime($ot7);

        $saturday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::SATURDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot8 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $ot9 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('17:00:00', '20:00:00');
        $saturday->addOpeningTime($ot8);
        $saturday->addOpeningTime($ot9);

        $weekscheme->setDay(SchemeDay::MONDAY, $monday);
        $weekscheme->setDay(SchemeDay::TUESDAY, $tuesday);
        $weekscheme->setDay(SchemeDay::WEDNESDAY, $wednesday);
        $weekscheme->setDay(SchemeDay::FRIDAY, $friday);
        $weekscheme->setDay(SchemeDay::SATURDAY, $saturday);


        $period->setWeekScheme($weekscheme);

        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            'Van 20 maart 2015 tot 27 maart 2015'. PHP_EOL
            . '(ma van 9:00 tot 13:00'. PHP_EOL . 'van 17:00 tot 20:00,' . PHP_EOL
            . 'di van 9:00 tot 13:00'. PHP_EOL . 'van 17:00 tot 20:00,'. PHP_EOL
            . 'wo van 9:00 tot 17:00,' . PHP_EOL
            . 'do  gesloten,' . PHP_EOL
            . 'vr van 9:00 tot 13:00'. PHP_EOL . 'van 17:00 tot 20:00,'. PHP_EOL
            . 'za van 9:00 tot 13:00' . PHP_EOL . 'van 17:00 tot 20:00,'. PHP_EOL
            . 'zo  gesloten)',
            $this->formatter->format($periodList)
        );
    }

    public function testFormatsAPeriodListWithoutUnnecessaryLineBreak()
    {
        $period_list = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $period = new CultureFeed_Cdb_Data_Calendar_Period('2020-09-20', '2020-09-21');
        $period_list->add($period);

        $expectedResult = 'Van 20 september 2020 tot 21 september 2020';

        $this->assertEquals(
            $expectedResult,
            $this->formatter->format($period_list)
        );
    }
}
