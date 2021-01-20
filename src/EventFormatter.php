<?php


namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\SearchV3\ValueObjects\Event;

interface EventFormatter
{
    public function format(Event $event): string;
}
