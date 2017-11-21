<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 26-3-15
 * Time: 10:27
 */

namespace CultuurNet\CalendarSummary\Timestamps;

use \CultureFeed_Cdb_Data_Calendar_TimestampList;
use \CultureFeed_Cdb_Data_Calendar_Timestamp;

class ExtraSmallTimestampsHTMLFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ExtraSmallTimestampsHTMLFormatter
     */
    protected $formatter;


    public function setUp()
    {
        $this->formatter = new ExtraSmallTimestampsHTMLFormatter();
    }

    public function testFormatsATimestamp()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-20', '09:00:00', '12:30:00');
        $timestamp_list->add($timestamp);

        $output = '<span class="cf-date">20</span>';
        $output .= ' ';
        $output .= '<span class="cf-month">sep</span>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsATimestampWithoutLeadingZero()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-09-09', '09:00:00', '12:30:00');
        $timestamp_list->add($timestamp);

        $output = '<span class="cf-date">9</span>';
        $output .= ' ';
        $output .= '<span class="cf-month">sep</span>';

        $this->assertEquals(
            $output,
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
            'xs format not supported for multiple timestamps.'
        );
        $this->formatter->format($timestamp_list);
    }
}
