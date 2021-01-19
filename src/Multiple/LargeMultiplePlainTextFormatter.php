<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\LargeSinglePlainTextFormatter;

final class LargeMultiplePlainTextFormatter implements MultipleFormatterInterface
{
    /**
     * @var string $langCode
     */
    private $langCode;

    /**
     * @var bool $hidePast
     */
    protected $hidePast;

    public function __construct(string $langCode, bool $hidePastDates)
    {
        $this->langCode = $langCode;
        $this->hidePast = $hidePastDates;
    }

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

            if ($this->hidePast) {
                if ($subEvent->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get())) > $now) {
                    $output .= $formatter->format($event);
                    if ($key + 1 !== $count) {
                        $output .= PHP_EOL;
                    }
                }
            } else {
                $output .= $formatter->format($event);
                if ($key + 1 !== $count) {
                    $output .= PHP_EOL;
                }
            }
        }

        return $output;
    }
}
