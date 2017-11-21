<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 06/03/15
 * Time: 13:52
 */

namespace CultuurNet\CalendarSummary\Permanent;

interface PermanentFormatterInterface
{

    /**
     * @param \CultureFeed_Cdb_Data_Calendar_Permanent $permanent
     * @return string
     */
    public function format(\CultureFeed_Cdb_Data_Calendar_Permanent $permanent);
}
