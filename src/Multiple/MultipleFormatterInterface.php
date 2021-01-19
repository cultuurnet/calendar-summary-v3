<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;

interface MultipleFormatterInterface
{
    public function format(Event $event): string;
}
