<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\MediumSingleHTMLFormatter;

/**
 * Formatter to format multiple events as html in medium format.
 */
class MediumMultipleHTMLFormatter implements MultipleFormatterInterface
{

    /**
     * {@inheritdoc}
     */
    public function format(Event $event)
    {
        $subEvents = $event->getSubEvents();
        $output = '';

        foreach ($subEvents as $subEvent) {
            $formatter = new MediumSingleHTMLFormatter();

            $event = new Event();
            $event->setStartDate($subEvent->getStartDate());
            $event->setEndDate($subEvent->getEndDate());

            $output .= $formatter->format($event);
        }

        return $output;
    }
}
