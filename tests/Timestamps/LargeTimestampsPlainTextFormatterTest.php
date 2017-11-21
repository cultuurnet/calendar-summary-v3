<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 27/03/15
 * Time: 17:46
 */

namespace CultuurNet\CalendarSummary\Timestamps;

use \CultureFeed_Cdb_Data_Calendar_TimestampList;
use \CultureFeed_Cdb_Data_Calendar_Timestamp;

class LargeTimestampsPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargeTimestampsPlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new LargeTimestampsPlainTextFormatter();

        // Needs to be between 2016 and 2020.
        $this->formatter->setShowFrom(strtotime('2018-01-01'));
    }

    public function testFormatsATimestampWithStartTime()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();
        $timestamp = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-09-20', '09:00:00');
        $timestamp_list->add($timestamp);

        $output = 'zondag 20 september 2020' . PHP_EOL;
        $output .= 'om 09:00';

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

        $output = 'woensdag 9 september 2020' . PHP_EOL;
        $output .= 'om 09:00';

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

        $output = 'zondag 20 september 2020' . PHP_EOL;
        $output .= 'van 09:00 tot 12:30';

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

        $output = 'zo 20 september 2020' . PHP_EOL;
        $output .= 'om 09:00' . PHP_EOL;
        $output .= 'ma 21 september 2020' . PHP_EOL;
        $output .= 'om 10:00' . PHP_EOL;
        $output .= 'di 22 september 2020' . PHP_EOL;
        $output .= 'om 09:00';

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

        $output = 'zo 20 september 2020' . PHP_EOL;
        $output .= 'van 09:00 tot 17:00' . PHP_EOL;
        $output .= 'ma 21 september 2020' . PHP_EOL;
        $output .= 'van 10:00 tot 18:00' . PHP_EOL;
        $output .= 'di 22 september 2020' . PHP_EOL;
        $output .= 'van 09:00 tot 17:00';

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

        $output = 'zo 20 september 2020' . PHP_EOL;
        $output .= 'om 09:00' . PHP_EOL;
        $output .= 'ma 21 september 2020' . PHP_EOL;
        $output .= 'van 10:00 tot 18:00' . PHP_EOL;
        $output .= 'di 22 september 2020' . PHP_EOL;
        $output .= 'van 09:00 tot 17:00';

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

        $output = 'ma 7 september 2020' . PHP_EOL;
        $output .= 'om 09:00' . PHP_EOL;
        $output .= 'di 8 september 2020' . PHP_EOL;
        $output .= 'van 10:00 tot 18:00' . PHP_EOL;
        $output .= 'wo 9 september 2020' . PHP_EOL;
        $output .= 'van 09:00 tot 17:00';

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

        $output = 'ma 21 september 2020' . PHP_EOL;
        $output .= 'van 10:00 tot 18:00' . PHP_EOL;
        $output .= 'di 22 september 2020' . PHP_EOL;
        $output .= 'van 09:00 tot 17:00';

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

        $output = 'Van do 21 mei 2020 10:00' . PHP_EOL;
        $output .= 'tot za 23 mei 2020 16:00';

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
        $timestamp4 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-06-24', '10:00:02');
        $timestamp5 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-06-25', '00:00:02');
        $timestamp6 = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-06-26', '00:00:02', '16:00:00');
        $timestamp_list->add($timestamp1);
        $timestamp_list->add($timestamp2);
        $timestamp_list->add($timestamp3);
        $timestamp_list->add($timestamp4);
        $timestamp_list->add($timestamp5);
        $timestamp_list->add($timestamp6);

        $output = 'Van do 21 mei 2020 10:00' . PHP_EOL;
        $output .= 'tot za 23 mei 2020 16:00' . PHP_EOL;
        $output .= 'Van wo 24 juni 2020 10:00' . PHP_EOL;
        $output .= 'tot vr 26 juni 2020 16:00';

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

        $output = 'ma 25 mei 2020' . PHP_EOL;
        $output .= 'van 10:00 tot 16:00' . PHP_EOL;
        $output .= 'ma 25 mei 2020' . PHP_EOL;
        $output .= 'van 20:00 tot 01:00' . PHP_EOL;
        $output .= 'Van zo 28 juni 2020 10:00' . PHP_EOL;
        $output .= 'tot di 30 juni 2020 16:00';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }

    public function testFormatsTimestampsMixedWithPeriodComplex()
    {
        $timestamp_list = new CultureFeed_Cdb_Data_Calendar_TimestampList();

        $timestamps[] = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-12', '10:00:01');
        $timestamps[] = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-13', '00:00:01');
        $timestamps[] = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-14', '00:00:01', '14:00:00');

        $timestamps[] = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-20', '10:00:00', '14:00:00');

        $timestamps[] = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-25', '10:00:02');
        $timestamps[] = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-26', '00:00:02');
        $timestamps[] = new CultureFeed_Cdb_Data_Calendar_Timestamp('2020-05-27', '00:00:02', '14:00:00');

        foreach ($timestamps as $timestamp) {
            $timestamp_list->add($timestamp);
        }

        $output = 'Van di 12 mei 2020 10:00' . PHP_EOL;
        $output .= 'tot do 14 mei 2020 14:00' . PHP_EOL;
        $output .= 'wo 20 mei 2020' . PHP_EOL;
        $output .= 'van 10:00 tot 14:00' . PHP_EOL;
        $output .= 'Van ma 25 mei 2020 10:00' . PHP_EOL;
        $output .= 'tot wo 27 mei 2020 14:00';

        $this->assertEquals(
            $output,
            $this->formatter->format($timestamp_list)
        );
    }
}
