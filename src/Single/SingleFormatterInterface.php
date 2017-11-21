<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 06/03/15
 * Time: 10:54
 */

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Event;

interface SingleFormatterInterface
{

    /**
     * @param Event $offer
     * @return string
     */
    public function format(Event $event);
}
