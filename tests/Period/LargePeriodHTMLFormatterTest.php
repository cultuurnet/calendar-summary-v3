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

class LargePeriodHTMLFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargePeriodHTMLFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new LargePeriodHTMLFormatter();
    }

    public function testFormatsAPeriod()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2015-03-20',
            '2015-03-27'
        );

        $weekscheme=new \CultureFeed_Cdb_Data_Calendar_Weekscheme();

        $monday=new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::MONDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot1 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $ot2 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('17:00:00', '20:00:00');
        $monday->addOpeningTime($ot1);
        $monday->addOpeningTime($ot2);

        $tuesday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::TUESDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot3 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $ot4= new \CultureFeed_Cdb_Data_Calendar_OpeningTime('17:00:00', '20:00:00');
        $tuesday->addOpeningTime($ot3);
        $tuesday->addOpeningTime($ot4);

        $wednesday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::WEDNESDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot3b = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $ot4b = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('17:00:00', '20:00:00');
        $wednesday->addOpeningTime($ot3b);
        $wednesday->addOpeningTime($ot4b);

        $thursday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::THURSDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot5 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '17:00:00');
        $thursday->addOpeningTime($ot5);

        $friday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::FRIDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
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
        $weekscheme->setDay(SchemeDay::THURSDAY, $thursday);
        $weekscheme->setDay(SchemeDay::FRIDAY, $friday);
        $weekscheme->setDay(SchemeDay::SATURDAY, $saturday);


        $period->setWeekScheme($weekscheme);

        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            '<p class="cf-period"> <time itemprop="startDate" datetime="2015-03-20"> '
            . '<span class="cf-date">20 maart 2015</span> </time> <span class="cf-to cf-meta">tot</span> '
            . '<time itemprop="endDate" datetime="2015-03-27"> <span class="cf-date">27 maart 2015</span> </time> '
            . '</p> <p class="cf-openinghours">Open op:</p> <ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Mo-We 9:00-20:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> <span class="cf-days">Maandag - woensdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> <span itemprop="closes" content="13:00" class="cf-to cf-meta">tot'
            . '</span> <span class="cf-time">13:00</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">'
            . 'van</span> <span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">'
            . 'tot</span> <span class="cf-time">20:00</span> '
            . '</li> <meta itemprop="openingHours" datetime="Th 9:00-17:00"> '
            . '</meta> <li itemprop="openingHoursSpecification"> <span class="cf-days">Donderdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> <meta itemprop="openingHours" datetime="Fr-Sa 9:00-20:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> <span class="cf-days">Vrijdag - zaterdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '</li> </ul>',
            $this->formatter->format($periodList)
        );
    }

    public function testFormatsAPeriodWithoutWeekscheme()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2015-03-20',
            '2015-03-27'
        );

        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            '<p class="cf-period"> '
            . '<time itemprop="startDate" datetime="2015-03-20"> '
            . '<span class="cf-date">20 maart 2015</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<time itemprop="endDate" datetime="2015-03-27"> '
            . '<span class="cf-date">27 maart 2015</span> '
            . '</time> '
            . '</p>',
            $this->formatter->format($periodList)
        );
    }

    public function testFormatsAWithMidnights()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2015-03-20',
            '2015-03-27'
        );

        $weekscheme=new \CultureFeed_Cdb_Data_Calendar_Weekscheme();

        $monday=new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::MONDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot1 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $ot2 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('17:00:00', '20:00:00');
        $monday->addOpeningTime($ot1);
        $monday->addOpeningTime($ot2);

        $tuesday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::TUESDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot3 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $ot4= new \CultureFeed_Cdb_Data_Calendar_OpeningTime('17:00:00', '20:00:00');
        $tuesday->addOpeningTime($ot3);
        $tuesday->addOpeningTime($ot4);

        $wednesday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::WEDNESDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot3b = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '13:00:00');
        $ot4b = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('17:00:00', '20:00:00');
        $wednesday->addOpeningTime($ot3b);
        $wednesday->addOpeningTime($ot4b);

        $thursday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::THURSDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
        $ot5 = new \CultureFeed_Cdb_Data_Calendar_OpeningTime('09:00:00', '00:00:00');
        $thursday->addOpeningTime($ot5);

        $friday = new \CultureFeed_Cdb_Data_Calendar_SchemeDay(
            SchemeDay::FRIDAY,
            SchemeDay::SCHEMEDAY_OPEN_TYPE_OPEN
        );
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
        $weekscheme->setDay(SchemeDay::THURSDAY, $thursday);
        $weekscheme->setDay(SchemeDay::FRIDAY, $friday);
        $weekscheme->setDay(SchemeDay::SATURDAY, $saturday);


        $period->setWeekScheme($weekscheme);

        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            '<p class="cf-period"> <time itemprop="startDate" datetime="2015-03-20"> '
            . '<span class="cf-date">20 maart 2015</span> </time> <span class="cf-to cf-meta">tot</span> '
            . '<time itemprop="endDate" datetime="2015-03-27"> <span class="cf-date">27 maart 2015</span> </time> '
            . '</p> <p class="cf-openinghours">Open op:</p> <ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Mo-We 9:00-20:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> <span class="cf-days">Maandag - woensdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> <span itemprop="closes" content="13:00" class="cf-to cf-meta">tot'
            . '</span> <span class="cf-time">13:00</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">'
            . 'van</span> <span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">'
            . 'tot</span> <span class="cf-time">20:00</span> '
            . '</li> <meta itemprop="openingHours" datetime="Th 9:00-0:00"> '
            . '</meta> <li itemprop="openingHoursSpecification"> <span class="cf-days">Donderdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="0:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">0:00</span> '
            . '</li> <meta itemprop="openingHours" datetime="Fr-Sa 9:00-20:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> <span class="cf-days">Vrijdag - zaterdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '</li> </ul>',
            $this->formatter->format($periodList)
        );
    }
}
