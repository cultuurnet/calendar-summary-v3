<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 26-3-15
 * Time: 10:28
 */

namespace CultuurNet\CalendarSummary\Timestamps;

use \CultureFeed_Cdb_Data_Calendar_TimestampList;
use \CultureFeed_Cdb_Data_Calendar_Timestamp;

class LargeTimestampsHTMLFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargeTimestampsHTMLFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new LargeTimestampsHTMLFormatter();

        // Needs to be between 2016 and 2020.
        $this->formatter->setShowFrom(strtotime('2018-01-01'));
    }

    public function testFormatsATimestampWithStartTime()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20', '09:00:00');
        $timestamp_list->add($timestamp);

        $output = '<time itemprop="startDate" datetime="2020-09-20T09:00">';
        $output .= '<span class="cf-weekday cf-meta">zondag</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">20 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">om</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsATimestampWithStartTimeWithoutLeadingZero()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-09', '09:00:00');
        $timestamp_list->add($timestamp);

        $output = '<time itemprop="startDate" datetime="2020-09-09T09:00">';
        $output .= '<span class="cf-weekday cf-meta">woensdag</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">9 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">om</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsATimestampWithStartTimeAndEndTime()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20', '09:00:00', '12:30:00');
        $timestamp_list->add($timestamp);

        $output = '<time itemprop="startDate" datetime="2020-09-20T09:00">';
        $output .= '<span class="cf-weekday cf-meta">zondag</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">20 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-20T12:30">';
        $output .= '<span class="cf-time">12:30</span>';
        $output .= '</time>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleTimestampsWithStartTime()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20', '09:00:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-21', '10:00:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-22', '09:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $output = '<ul class="list-unstyled">';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-20T09:00">';
        $output .= '<span class="cf-weekday cf-meta">zo</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">20 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">om</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-21T10:00">';
        $output .= '<span class="cf-weekday cf-meta">ma</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">21 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">om</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">10:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-22T09:00">';
        $output .= '<span class="cf-weekday cf-meta">di</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">22 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">om</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '</ul>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsASingleTimestampBeginningOfYear()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2016-01-03', '19:00:00', '23:00:00');
        $timestamp_list->add($timestamp);

        $output = '<time itemprop="startDate" datetime="2016-01-03T19:00">';
        $output .= '<span class="cf-weekday cf-meta">zondag</span> ';
        $output .= '<span class="cf-date">3 januari 2016</span> ';
        $output .= '<span class="cf-from cf-meta">van</span> ';
        $output .= '<span class="cf-time">19:00</span></time> ';
        $output .= '<span class="cf-to cf-meta">tot</span> ';
        $output .= '<time itemprop="endDate" datetime="2016-01-03T23:00">';
        $output .= '<span class="cf-time">23:00</span></time>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleTimestampsWithStartTimeAndEndTime()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20', '09:00:00', '17:00:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-21', '10:00:00', '18:00:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-22', '09:00:00', '17:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $output = '<ul class="list-unstyled">';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-20T09:00">';
        $output .= '<span class="cf-weekday cf-meta">zo</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">20 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-20T17:00">';
        $output .= '<span class="cf-time">17:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-21T10:00">';
        $output .= '<span class="cf-weekday cf-meta">ma</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">21 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">10:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-21T18:00">';
        $output .= '<span class="cf-time">18:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-22T09:00">';
        $output .= '<span class="cf-weekday cf-meta">di</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">22 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-22T17:00">';
        $output .= '<span class="cf-time">17:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '</ul>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleTimestampsWithStartTimeOrStartTimeAndEndTime()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20', '09:00:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-21', '10:00:00', '18:00:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-22', '09:00:00', '17:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $output = '<ul class="list-unstyled">';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-20T09:00">';
        $output .= '<span class="cf-weekday cf-meta">zo</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">20 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">om</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-21T10:00">';
        $output .= '<span class="cf-weekday cf-meta">ma</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">21 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">10:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-21T18:00">';
        $output .= '<span class="cf-time">18:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-22T09:00">';
        $output .= '<span class="cf-weekday cf-meta">di</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">22 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-22T17:00">';
        $output .= '<span class="cf-time">17:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '</ul>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleTimestampsWithStartTimeOrStartTimeAndEndTimeWithoutLeadingZero()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-07', '09:00:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-08', '10:00:00', '18:00:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-09', '09:00:00', '17:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $output = '<ul class="list-unstyled">';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-07T09:00">';
        $output .= '<span class="cf-weekday cf-meta">ma</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">7 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">om</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-08T10:00">';
        $output .= '<span class="cf-weekday cf-meta">di</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">8 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">10:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-08T18:00">';
        $output .= '<span class="cf-time">18:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-09T09:00">';
        $output .= '<span class="cf-weekday cf-meta">wo</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">9 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-09T17:00">';
        $output .= '<span class="cf-time">17:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '</ul>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleTimestampsWithPastAndFutureTimestamps()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-03-26', '09:00:00', '17:00:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-21', '10:00:00', '18:00:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-22', '09:00:00', '17:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $output = '<ul class="list-unstyled">';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-21T10:00">';
        $output .= '<span class="cf-weekday cf-meta">ma</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">21 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">10:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-21T18:00">';
        $output .= '<span class="cf-time">18:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-22T09:00">';
        $output .= '<span class="cf-weekday cf-meta">di</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">22 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-22T17:00">';
        $output .= '<span class="cf-time">17:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '</ul>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsATimestampWithoutTimes()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20');
        $timestamp_list->add($timestamp);

        $output = '<time itemprop="startDate" datetime="2020-09-20">';
        $output .= '<span class="cf-weekday cf-meta">zondag</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">20 september 2020</span>';
        $output .= ' ';
        $output .= '</time>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleTimestampsAtMidnight()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2015-03-26', '09:00:00', '17:00:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-21', '10:00:00', '00:00:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-22', '09:00:00', '17:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $output = '<ul class="list-unstyled">';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-21T10:00">';
        $output .= '<span class="cf-weekday cf-meta">ma</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">21 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">10:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-21T00:00">';
        $output .= '<span class="cf-time">00:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-09-22T09:00">';
        $output .= '<span class="cf-weekday cf-meta">di</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">22 september 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">09:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-09-22T17:00">';
        $output .= '<span class="cf-time">17:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '</ul>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleIndexedTimestampsThatMakeAPeriod()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-21', '10:00:01');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-22', '00:00:01');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-23', '00:00:01', '16:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);

        $output = '<ul class="list-unstyled">';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-05-21T10:00">';
        $output .= '<span class="cf-weekday cf-meta">do</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">21 mei 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">10:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-05-23T16:00">';
        $output .= '<span class="cf-weekday cf-meta">za</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">23 mei 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">16:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '</ul>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsMultipleIndexedTimestampsAsMultiplePeriods()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-21', '10:00:01');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-22', '00:00:01');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-23', '00:00:01', '16:00:00');
        $timestamp4 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-24', '10:00:02');
        $timestamp5 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-25', '00:00:02');
        $timestamp6 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-26', '00:00:02', '16:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);
        $timestamp_list->add($timestamp4);
        $timestamp_list->add($timestamp5);
        $timestamp_list->add($timestamp6);

        $output = '<ul class="list-unstyled">';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-05-21T10:00">';
        $output .= '<span class="cf-weekday cf-meta">do</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">21 mei 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">10:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-05-23T16:00">';
        $output .= '<span class="cf-weekday cf-meta">za</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">23 mei 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">16:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-05-24T10:00">';
        $output .= '<span class="cf-weekday cf-meta">zo</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">24 mei 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">10:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-05-26T16:00">';
        $output .= '<span class="cf-weekday cf-meta">di</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">26 mei 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">16:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '</ul>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsTimestampsMixedWithPeriod()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp1 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-25', '10:00:00', '16:00:00');
        $timestamp2 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-25', '20:00:00', '01:00:00');
        $timestamp3 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-06-28', '10:00:01');
        $timestamp4 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-06-29', '00:00:01');
        $timestamp5 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-06-30', '00:00:01', '16:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);
        $timestamp_list->add($timestamp4);
        $timestamp_list->add($timestamp5);

        $output = '<ul class="list-unstyled">';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-05-25T10:00">';
        $output .= '<span class="cf-weekday cf-meta">ma</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">25 mei 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">10:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-05-25T16:00">';
        $output .= '<span class="cf-time">16:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-05-25T20:00">';
        $output .= '<span class="cf-weekday cf-meta">ma</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">25 mei 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">20:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-05-26T01:00">';
        $output .= '<span class="cf-time">01:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '<li>';
        $output .= '<time itemprop="startDate" datetime="2020-06-28T10:00">';
        $output .= '<span class="cf-weekday cf-meta">zo</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">28 juni 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">10:00</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="2020-06-30T16:00">';
        $output .= '<span class="cf-weekday cf-meta">di</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">30 juni 2020</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">16:00</span>';
        $output .= '</time>';
        $output .= '</li>';
        $output .= '</ul>';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }
}
