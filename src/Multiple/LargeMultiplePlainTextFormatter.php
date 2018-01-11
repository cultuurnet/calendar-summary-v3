<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\LargeSinglePlainTextFormatter;

class LargeMultiplePlainTextFormatter implements MultipleFormatterInterface
{

    /**
    * Return large formatted multiple date string.
    *
    * @param \CultuurNet\SearchV3\ValueObjects\Event $event
    * @return string
    */
    public function format(Event $event)
    {
        $subEvents = $event->getSubEvents();
        $count = count($subEvents);
        $output = '';

        foreach ($subEvents as $key => $subEvent) {
            $formatter = new LargeSinglePlainTextFormatter();

            $event = new Event();
            $event->setStartDate(new \DateTime($subEvent['startDate']));
            $event->setEndDate(new \DateTime($subEvent['endDate']));

            $output .= $formatter->format($event);
            if ($key + 1 !== $count) {
                $output .= PHP_EOL;
            }
        }

        return $output;
    }
}
