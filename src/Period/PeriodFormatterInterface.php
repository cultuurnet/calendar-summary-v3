<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 06/03/15
 * Time: 14:07
 */

namespace CultuurNet\CalendarSummary\Period;

interface PeriodFormatterInterface
{

    /**
     * @param \CultureFeed_Cdb_Data_Calendar_PeriodList $period
     * @return string
     */
    public function format(\CultureFeed_Cdb_Data_Calendar_PeriodList $period);
}
