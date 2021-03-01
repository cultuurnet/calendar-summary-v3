<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\MediumSingleHTMLFormatter;

final class MediumMultipleHTMLFormatter implements MultipleFormatterInterface
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var bool
     */
    private $hidePast;

    public function __construct(Translator $translator, bool $hidePastDates)
    {
        $this->translator = $translator;
        $this->hidePast = $hidePastDates;
    }

    public function format(Event $event): string
    {
        $subEvents = $event->getSubEvents();
        $output = '<ul class="cnw-event-date-info">';

        foreach ($subEvents as $subEvent) {
            $formatter = new MediumSingleHTMLFormatter($this->translator);

            if (!$this->hidePast || DateComparison::inTheFuture($subEvent->getEndDate())) {
                $output .= '<li>' . $formatter->format($subEvent) . '</li>';
            }
        }

        $output .= '</ul>';

        return $output;
    }
}
