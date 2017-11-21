<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 27/03/15
 * Time: 17:17
 */

namespace CultuurNet\CalendarSummary\Timestamps;

use \CultureFeed_Cdb_Data_Calendar_TimestampList;
use \CultureFeed_Cdb_Data_Calendar_Timestamp;

class MediumTimestampsPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MediumTimestampsFormatter
     */
    protected $formatter;


    public function setUp()
    {
        $this->formatter = new MediumTimestampsPlainTextFormatter();
    }

    public function testFormatsASingleTimestamp()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20', '09:00:00');
        $timestamp_list->add($timestamp);

        $this->assertEquals(
            'zondag 20 september 2020',
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsASingleTimestampWithoutLeadingZero()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-09', '09:00:00');
        $timestamp_list->add($timestamp);

        $this->assertEquals(
            'woensdag 9 september 2020',
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleTimestamps()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20', '09:00:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-21', '09:00:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-22', '09:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $output = 'Van 20 september 2020' .PHP_EOL;
        $output .= 'tot 22 september 2020';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleTimestampsWithoutLeadingZero()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-07', '09:00:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-08', '09:00:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-09', '09:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $output = 'Van 7 september 2020' . PHP_EOL;
        $output .= 'tot 9 september 2020';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleTimestampsWithSameBeginAndEndDate()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20', '09:00:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20', '09:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);

        $this->assertEquals(
            'zondag 20 september 2020',
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleIndexedTimestampsThatMakeAPeriod()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-05-21', '10:00:01');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-05-22', '00:00:01');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-05-23', '00:00:01', '16:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $output = 'Van 21 mei 2017' . PHP_EOL;
        $output .= 'tot 23 mei 2017';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleIndexedTimestampsAsOnePeriodAndIgnoresIndex()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-05-21', '10:00:01');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-05-22', '00:00:01');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-05-23', '00:00:01', '16:00:00');
        $timestamp4 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-06-24', '10:00:02');
        $timestamp5 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-06-25', '00:00:02');
        $timestamp6 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-06-26', '00:00:02', '16:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);
        $timestamp_list->add($timestamp4);
        $timestamp_list->add($timestamp5);
        $timestamp_list->add($timestamp6);

        $output = 'Van 21 mei 2017' . PHP_EOL;
        $output .= 'tot 26 juni 2017';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsTimestampsMixedWithPeriodAsOnePeriod()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-05-25', '10:00:00', '16:00:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-05-25', '20:00:00', '01:00:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-06-28', '10:00:01');
        $timestamp4 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-06-29', '00:00:01');
        $timestamp5 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2017-06-30', '00:00:01', '16:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);
        $timestamp_list->add($timestamp4);
        $timestamp_list->add($timestamp5);

        $output = 'Van 25 mei 2017' . PHP_EOL;
        $output .= 'tot 30 juni 2017';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }
}
