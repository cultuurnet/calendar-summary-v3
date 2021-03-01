<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;

interface MultipleFormatterInterface
{
    public function format(Event $event): string;
}
