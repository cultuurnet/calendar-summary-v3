<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Single\LargeSingleHTMLFormatter;

final class LargeMultipleHTMLFormatter implements MultipleFormatterInterface
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

    public function format(Offer $offer): string
    {
        $subEvents = $offer->getSubEvents();
        $formatter = new LargeSingleHTMLFormatter($this->translator);

        $subEventSummaries = [];
        foreach ($subEvents as $subEvent) {
            if (!$this->hidePast || DateComparison::inTheFuture($subEvent->getEndDate())) {
                $subEventSummaries[] = $formatter->format($subEvent);
            }
        }

        if (empty($subEventSummaries)) {
            return '<span>' . $this->translator->translate('event_concluded') . '</span>';
        }

        $output = '<ul class="cnw-event-date-info">';
        foreach ($subEventSummaries as $subEventSummary) {
            $output .= '<li>' . $subEventSummary . '</li>';
        }
        $output .= '</ul>';

        return $output;
    }
}
