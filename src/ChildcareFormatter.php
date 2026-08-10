<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Childcare;

final class ChildcareFormatter
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Used when every day has the same childcare, e.g. 'Elke dag opvang van 8:00 tot 18:00'.
     */
    public function formatForEveryDay(Childcare $childcare): string
    {
        return ucfirst($this->translator->translate('every_day')) . ' ' . $this->format($childcare);
    }

    /**
     * Used when the childcare differs per day, e.g. '(opvang van 8:00 tot 17:00)'.
     */
    public function formatBetweenParentheses(Childcare $childcare): string
    {
        return '(' . $this->format($childcare) . ')';
    }

    private function format(Childcare $childcare): string
    {
        $start = $childcare->getStart();
        $end = $childcare->getEnd();

        // Childcare that only happens before the opening hours has no end.
        if ($end === null) {
            return $this->translator->translate('childcare_before')
                . ' ' . $this->translator->translate('childcare_from')
                . ' ' . OpeningHourFormatter::format($start);
        }

        // Childcare that only happens after the opening hours has no start.
        if ($start === null) {
            return $this->translator->translate('childcare_after')
                . ' ' . $this->translator->translate('childcare_till')
                . ' ' . OpeningHourFormatter::format($end);
        }

        return $this->translator->translate('childcare')
            . ' ' . $this->translator->translate('from_hour')
            . ' ' . OpeningHourFormatter::format($start)
            . ' ' . $this->translator->translate('till_hour')
            . ' ' . OpeningHourFormatter::format($end);
    }
}
