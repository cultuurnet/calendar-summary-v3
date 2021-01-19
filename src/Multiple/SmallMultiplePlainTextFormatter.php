<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicPlainTextFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

class SmallMultiplePlainTextFormatter implements MultipleFormatterInterface
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
        $formatter = new MediumPeriodicPlainTextFormatter($this->langCode);
        return $formatter->format($event);
    }
}
