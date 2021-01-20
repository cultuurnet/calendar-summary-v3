<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\MediumSingleHTMLFormatter;

final class MediumMultipleHTMLFormatter implements MultipleFormatterInterface
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
        $output = '<ul class="cnw-event-date-info">';

        foreach ($subEvents as $subEvent) {
            $formatter = new MediumSingleHTMLFormatter($this->langCode);

            if (!$this->hidePast || DateComparison::inTheFuture($subEvent->getEndDate())) {
                $output .= '<li>' . $formatter->format($subEvent) . '</li>';
            }
        }

        $output .= '</ul>';

        return $output;
    }
}
