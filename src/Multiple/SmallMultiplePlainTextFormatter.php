<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicPlainTextFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

/**
 * Formatter to format multiple events as plain text in medium format.
 */
class SmallMultiplePlainTextFormatter extends SmallMultipleFormatter implements MultipleFormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format(Event $event)
    {
        $formatter = new MediumPeriodicPlainTextFormatter($this->langCode);
        return $formatter->format($event);
    }
}