<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicHTMLFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

final class SmallMultipleHTMLFormatter implements MultipleFormatterInterface
{
    /**
     * @var string
     */
    protected $langCode;

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
