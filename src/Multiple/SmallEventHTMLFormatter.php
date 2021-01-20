<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\EventFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicHTMLFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

final class SmallEventHTMLFormatter implements EventFormatter
{
    /**
     * @var string
     */
    private $langCode;

    public function __construct(string $langCode)
    {
        $this->langCode = $langCode;
    }

    public function format(Event $event): string
    {
        $formatter = new MediumPeriodicHTMLFormatter($this->langCode);
        return $formatter->format($event);
    }
}
