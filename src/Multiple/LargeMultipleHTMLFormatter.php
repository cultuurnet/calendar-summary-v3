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
        $subEvents = [];
        foreach ($offer->getSubEvents() as $subEvent) {
            if (!$this->hidePast || DateComparison::isInTheFuture($subEvent->getEndDate())) {
                $subEvents[] = $subEvent;
            }
        }

        // Every sub-event gets its own list item, so its childcare is nested inside of it. A
        // date without childcare only reports that when a listed one does have it, otherwise
        // an offer that never has childcare would repeat it on every single date.
        $formatter = new LargeSingleHTMLFormatter(
            $this->translator,
            true,
            $this->anyHasChildcare($subEvents)
        );

        $subEventSummaries = [];
        foreach ($subEvents as $subEvent) {
            $subEventSummaries[] = $formatter->format($subEvent);
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

    /**
     * @param Offer[] $subEvents
     */
    private function anyHasChildcare(array $subEvents): bool
    {
        foreach ($subEvents as $subEvent) {
            if ($subEvent->getChildcare() !== null) {
                return true;
            }
        }

        return false;
    }
}
