<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicHTMLFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;

/**
 * Formatter to format multiple events as html in medium format.
 */
class SmallMultipleHTMLFormatter extends SmallMultipleFormatter implements MultipleFormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format(Event $event)
    {
        $formatter = new MediumPeriodicHTMLFormatter($this->langCode);
        return $formatter->format($event);
    }
}