<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;

interface MultipleFormatterInterface
{

    /**
     * @param \CultuurNet\SearchV3\ValueObjects\Event $event
     * @return string
     */
    public function format(Event $event);
}
