<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 06/03/15
 * Time: 10:54
 */

namespace CultuurNet\CalendarSummaryV3\Timestamps;

interface TimestampsFormatterInterface
{

    /**
     * @param \CultureFeed_Cdb_Data_Calendar_TimestampList $timestampList
     * @return string
     */
    public function format(
        \CultureFeed_Cdb_Data_Calendar_TimestampList $timestampList
    );
}
