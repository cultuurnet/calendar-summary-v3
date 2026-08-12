<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\ClosedDay;

/**
 * Renders the periods during which there is no opening at all as a collapsible list.
 */
final class HtmlClosedDaysFormatter
{
    private HtmlPeriodListFormatter $periodListFormatter;

    public function __construct(Translator $translator)
    {
        $this->periodListFormatter = new HtmlPeriodListFormatter($translator);
    }

    /**
     * @param ClosedDay[] $closedDays
     */
    public function format(array $closedDays): string
    {
        return $this->periodListFormatter->format($closedDays, 'cf-closed-days', 'closed');
    }
}
