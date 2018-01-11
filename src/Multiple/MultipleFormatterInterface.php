<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;

interface MultipleFormatterInterface
{

    /**
     * Format the event with multiple info.
     *
     * @param \CultuurNet\SearchV3\ValueObjects\Event $event
     * @return string
     */
    public function format(Event $event);
}
