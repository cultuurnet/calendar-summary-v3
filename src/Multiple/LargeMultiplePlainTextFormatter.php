<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\LargeSinglePlainTextFormatter;

/**
 * Formatter to format multiple events as plain text in large format.
 */
class LargeMultiplePlainTextFormatter implements MultipleFormatterInterface
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
            $formatter = new LargeSinglePlainTextFormatter();

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
