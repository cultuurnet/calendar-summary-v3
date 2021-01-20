<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\EventFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\MediumSinglePlainTextFormatter;

final class MediumEventPlainTextFormatter implements EventFormatter
{
    /**
     * @var string $langCode
     */
    private $langCode;

    /**
     * @var bool $hidepast
     */
    private $hidePast;

    public function __construct(string $langCode, bool $hidePastDates)
    {
        $this->langCode = $langCode;
        $this->hidePast = $hidePastDates;
    }

    public function format(Event $event): string
    {
        $subEvents = $event->getSubEvents();
        $now = new \DateTime();
        $output = [];

        foreach ($subEvents as $key => $subEvent) {
            $formatter = new MediumSinglePlainTextFormatter($this->langCode);

            $event = new Event();
            $event->setStartDate($subEvent->getStartDate());
            $event->setEndDate($subEvent->getEndDate());

            if (!$this->hidePast ||
                $subEvent->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get())) > $now) {
                $output[] = $formatter->format($event);
            }
        }

        return implode(PHP_EOL, $output);
    }
}
