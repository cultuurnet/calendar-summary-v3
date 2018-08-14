<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\MediumSingleHTMLFormatter;

/**
 * Formatter to format multiple events as html in medium format.
 */
class MediumMultipleHTMLFormatter extends MediumMultipleFormatter implements MultipleFormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format(Event $event)
    {
        $subEvents = $event->getSubEvents();
        $output = '';
        $now = new \DateTime();

        foreach ($subEvents as $subEvent) {
            $formatter = new MediumSingleHTMLFormatter($this->langCode);

            $event = new Event();
            $event->setStartDate($subEvent->getStartDate());
            $event->setEndDate($subEvent->getEndDate());

            if ($this->hidePast) {
                if ($subEvent->getEndDate() > $now) {
                    $output .= $formatter->format($event);
                }
            } else {
                $output .= $formatter->format($event);
            }
        }

        return $output;
    }
}
