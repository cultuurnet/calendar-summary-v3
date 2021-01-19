<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\MediumSingleHTMLFormatter;

class MediumMultipleHTMLFormatter extends MediumMultipleFormatter implements MultipleFormatterInterface
{
    /**
     * @var string $langCode
     */
    protected $langCode;

    /**
     * @var bool $hidepast
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
        $output = '<ul class="cnw-event-date-info">';
        $now = new \DateTime();

        foreach ($subEvents as $subEvent) {
            $formatter = new MediumSingleHTMLFormatter($this->langCode);

            $event = new Event();
            $event->setStartDate($subEvent->getStartDate());
            $event->setEndDate($subEvent->getEndDate());

            if ($this->hidePast) {
                if ($subEvent->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get())) > $now) {
                    $output .= '<li>' . $formatter->format($event) . '</li>';
                }
            } else {
                $output .= '<li>' . $formatter->format($event) . '</li>';
            }
        }

        $output .= '</ul>';

        return $output;
    }
}
