<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 03/04/15
 * Time: 14:08
 */
namespace CultuurNet\CalendarSummary;

use \CultureFeed_Cdb_Data_Calendar_Period;
use \CultureFeed_Cdb_Data_Calendar_PeriodList;
use \CultureFeed_Cdb_Data_Calendar_Permanent;
use \CultureFeed_Cdb_Data_Calendar_Timestamp;
use \CultureFeed_Cdb_Data_Calendar_TimestampList;
use \CultureFeed_Cdb_Data_Calendar_SchemeDay as SchemeDay;

class CalendarPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CalendarPlainTextFormatter
     */
    protected $formatter;


    public function setUp()
    {
        $this->formatter = new CalendarPlainTextFormatter();
    }

    public function testFormatsMultipleTimestampsWithUnexistingSmallFormat()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-20', '09:00:00', '12:30:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-21', '09:00:00', '12:30:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-22', '09:00:00', '12:30:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $this->setExpectedException(
            '\CultuurNet\CalendarSummary\FormatterException',
            'sm format not supported for multiple timestamps.'
        );
        $this->formatter->format($timestamp_list, 'sm');
    }

    public function testFormatsMultipleTimestampsWithUnexistingExtraSmallFormat()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-20', '09:00:00', '12:30:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-21', '09:00:00', '12:30:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-22', '09:00:00', '12:30:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $this->setExpectedException(
            '\CultuurNet\CalendarSummary\FormatterException',
            'xs format not supported for multiple timestamps.'
        );
        $this->formatter->format($timestamp_list, 'xs');
    }

    public function testFormatsMultipleTimestampsWithUnexistingCustomFormat()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-20', '09:00:00', '12:30:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-21', '09:00:00', '12:30:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-22', '09:00:00', '12:30:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $format = 'cnet';

        $this->setExpectedException(
            '\CultuurNet\CalendarSummary\FormatterException',
            $format . ' format not supported for CultureFeed_Cdb_Data_Calendar_TimestampList'
        );
        $this->formatter->format($timestamp_list, $format);
    }

    public function testFormatsMultipleTimestampsWithSmallFormat()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-20', '09:00:00', '12:30:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-21', '09:00:00', '12:30:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-22', '09:00:00', '12:30:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $this->setExpectedException(
            '\CultuurNet\CalendarSummary\FormatterException',
            'sm format not supported for multiple timestamps.'
        );
        $this->formatter->format($timestamp_list, 'sm');
    }

    public function testFormatsATimestampWithMediumFormat()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20', '09:00:00');
        $timestamp_list->add($timestamp);

        $this->assertEquals(
            'zondag 20 september 2020',
            $this->formatter->format($timestamp_list, 'md')
        );
    }

    public function testFormatsPermanentWithUnexistingSmallFormat()
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

        $format = 'sm';

        $this->setExpectedException(
            '\CultuurNet\CalendarSummary\FormatterException',
            $format . ' format not supported for CultureFeed_Cdb_Data_Calendar_Permanent'
        );
        $this->formatter->format($permanent, $format);
    }

    public function testFormatsPermanentWithUnexistingCustomFormat()
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

        $format = '1337';

        $this->setExpectedException(
            '\CultuurNet\CalendarSummary\FormatterException',
            $format . ' format not supported for CultureFeed_Cdb_Data_Calendar_Permanent'
        );
        $this->formatter->format($permanent, $format);
    }

    public function testFormatsAPeriodMedium()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2015-03-20',
            '2015-03-27'
        );
        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $this->assertEquals(
            'Van 20 maart 2015 tot 27 maart 2015',
            $this->formatter->format($periodList, 'md')
        );
    }

    public function testFormatsAPeriodWithUnexistingCustomFormat()
    {
        $period = new CultureFeed_Cdb_Data_Calendar_Period(
            '2015-03-20',
            '2015-03-27'
        );
        $periodList = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $periodList->add($period);

        $format = 'cnet';

        $this->setExpectedException(
            '\CultuurNet\CalendarSummary\FormatterException',
            $format . ' format not supported for CultureFeed_Cdb_Data_Calendar_PeriodList'
        );
        $this->formatter->format($periodList, $format);
    }
}
