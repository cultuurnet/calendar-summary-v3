<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 06/03/15
 * Time: 10:59
 */

namespace CultuurNet\CalendarSummary;

interface CalendarFormatterInterface
{
    public function format(\CultureFeed_Cdb_Data_Calendar $calendar, $format);
}
