<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\MediumSinglePlainTextFormatter;

/**
 * Formatter to format multiple events as plain text in medium format.
 */
class MediumMultiplePlainTextFormatter extends MediumMultipleFormatter implements MultipleFormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format(Event $event)
    {
        $subEvents = $event->getSubEvents();
        $count = count($subEvents);

        $output = '';

        foreach ($subEvents as $key => $subEvent) {
            $formatter = new MediumSinglePlainTextFormatter($this->langCode);

            $event = new Event();
            $event->setStartDate($subEvent->getStartDate());
            $event->setEndDate($subEvent->getEndDate());

            $output .= $formatter->format($event);
            if ($key + 1 !== $count) {
                $output .= PHP_EOL;
            }
        }

        return $output;
    }
}
