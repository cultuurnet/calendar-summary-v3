<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\LargeSinglePlainTextFormatter;

class LargeMultiplePlainTextFormatter extends LargeMultipleFormatter implements MultipleFormatterInterface
{
    public function format(Event $event): string
    {
        $subEvents = $event->getSubEvents();
        $count = count($subEvents);
        $output = '';
        $now = new \DateTime();

        foreach ($subEvents as $key => $subEvent) {
            $formatter = new LargeSinglePlainTextFormatter($this->langCode);

            $event = new Event();
            $event->setStartDate($subEvent->getStartDate());
            $event->setEndDate($subEvent->getEndDate());

            if (!$this->hidePast ||
                $subEvent->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get())) > $now) {
                $output .= $formatter->format($event);
                if ($key + 1 !== $count) {
                    $output .= PHP_EOL;
                }
            }
        }

        return $output;
    }
}
