<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 27/03/15
 * Time: 17:10
 */

namespace CultuurNet\CalendarSummary\Timestamps;

use \CultureFeed_Cdb_Data_Calendar_TimestampList;
use \CultureFeed_Cdb_Data_Calendar_Timestamp;

class SmallTimestampsPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SmallTimestampsFormatter
     */
    protected $formatter;


    public function setUp()
    {
        $this->formatter = new SmallTimestampsPlainTextFormatter();
    }

    public function testFormatsATimestamp()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-20', '09:00:00', '12:30:00');
        $timestamp_list->add($timestamp);

        $this->assertEquals(
            '20 sep',
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsATimestampWithoutLeadingZero()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-09', '09:00:00', '12:30:00');
        $timestamp_list->add($timestamp);

        $this->assertEquals(
            '9 sep',
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleTimestamps()
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
        $this->formatter->format($timestamp_list);
    }
}
