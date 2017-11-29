<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\MediumSinglePlainTextFormatter;

class MediumMultiplePlainTextFormatter implements MultipleFormatterInterface
{

    /**
    * Return medium formatted single date string.
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
            $formatter = new MediumSinglePlainTextFormatter();

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
