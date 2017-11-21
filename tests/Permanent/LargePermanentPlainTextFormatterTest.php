<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 26-3-15
 * Time: 10:27
 */

namespace CultuurNet\CalendarSummary\Permanent;

use \CultureFeed_Cdb_Data_Calendar_Permanent;
use \CultureFeed_Cdb_Data_Calendar_SchemeDay as SchemeDay;

class LargePermanentPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargePermanentPlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new LargePermanentPlainTextFormatter();
    }

    public function testFormatsAsSimplePermanent()
    {
        $permanent = new CultureFeed_Cdb_Data_Calendar_Permanent();

        $weekscheme = new \CultureFeed_Cdb_Data_Calendar_Weekscheme();

        $monday=new \CultureFeed_Cdb_Data_Calendar_SchemeDay(SchemeDay::MONDAY, SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN);
        $ot1 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $monday->addOpeningTime($ot1);

        $tuesday=new \CultureFeed_Cdb_Data_Calendar_SchemeDay(SchemeDay::TUESDAY, SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN);
        $ot2 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $tuesday->addOpeningTime($ot2);

        $wednesday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::WEDNESDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot3 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $wednesday->addOpeningTime($ot3);

        $thursday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::THURSDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot4 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $thursday->addOpeningTime($ot4);

        $friday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(SchemeDay::FRIDAY, SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN);
        $ot5 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $friday->addOpeningTime($ot5);

        $saturday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::SATURDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot6 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '19:00:00');
        $saturday->addOpeningTime($ot6);

        $sunday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::SUNDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot7 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '19:00:00');
        $sunday->addOpeningTime($ot6);

        $weekscheme->setDay(SchemeDay::MONDAY, $monday);
        $weekscheme->setDay(SchemeDay::TUESDAY, $tuesday);
        $weekscheme->setDay(SchemeDay::WEDNESDAY, $wednesday);
        //$weekscheme->setDay(SchemeDay::THURSDAY, $thursday);
        $weekscheme->setDay(SchemeDay::FRIDAY, $friday);
        $weekscheme->setDay(SchemeDay::SATURDAY, $saturday);
        $weekscheme->setDay(SchemeDay::SUNDAY, $sunday);

        $permanent->setWeekScheme($weekscheme);

        $this->assertEquals(
            'Ma Van 9:00 tot 13:00'. PHP_EOL . 'Di Van 9:00 tot 13:00'. PHP_EOL . 'Wo Van 9:00 tot 13:00' . PHP_EOL
            . 'Do  gesloten'. PHP_EOL . 'Vr Van 9:00 tot 13:00'. PHP_EOL . 'Za Van 9:00 tot 19:00'
            . PHP_EOL . 'Zo Van 9:00 tot 19:00' . PHP_EOL,
            $this->formatter->format($permanent)
        );
    }

    public function testFormatsAsMixedPermanent()
    {
        $permanent = new CultureFeed_Cdb_Data_Calendar_Permanent();

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


        $permanent->setWeekScheme($weekscheme);

        $this->assertEquals(
            'Ma Van 9:00 tot 13:00'. PHP_EOL . 'Van 17:00 tot 20:00'. PHP_EOL
            . 'Di Van 9:00 tot 13:00' . PHP_EOL . 'Van 17:00 tot 20:00'. PHP_EOL
            . 'Wo Van 9:00 tot 17:00'. PHP_EOL . 'Do  gesloten'. PHP_EOL
            . 'Vr Van 9:00 tot 13:00' . PHP_EOL . 'Van 17:00 tot 20:00'. PHP_EOL
            . 'Za Van 9:00 tot 13:00'. PHP_EOL . 'Van 17:00 tot 20:00'. PHP_EOL
            . 'Zo  gesloten' . PHP_EOL,
            $this->formatter->format($permanent)
        );
    }
}
