<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 06/03/15
 * Time: 10:54
 */

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;

interface MultipleFormatterInterface
{

    /**
     * @param Event $offer
     * @return string
     */
    public function format(Event $event);
}
