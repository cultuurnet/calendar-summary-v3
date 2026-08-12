<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\ClosedDay;

/**
 * Renders the periods during which there is no opening at all as plain text.
 */
final class PlainTextClosedDaysFormatter
{
    private PlainTextPeriodListFormatter $periodListFormatter;

    public function __construct(Translator $translator)
    {
        $this->periodListFormatter = new PlainTextPeriodListFormatter($translator);
    }

    /**
     * @param ClosedDay[] $closedDays
     */
    public function format(array $closedDays): string
    {
        return $this->periodListFormatter->format($closedDays, 'closed');
    }
}
