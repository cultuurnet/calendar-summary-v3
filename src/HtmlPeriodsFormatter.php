<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Offer;

/**
 * Renders the periods that deviate from the regular opening hours as HTML:
 * the adjusted days followed by the closed days.
 */
final class HtmlPeriodsFormatter
{
    private HtmlAdjustedDaysFormatter $adjustedDaysFormatter;

    private HtmlClosedDaysFormatter $closedDaysFormatter;

    public function __construct(Translator $translator)
    {
        $this->adjustedDaysFormatter = new HtmlAdjustedDaysFormatter($translator);
        $this->closedDaysFormatter = new HtmlClosedDaysFormatter($translator);
    }

    /**
     * Both formatters render a block of their own, so unlike the plain text
     * variant they need no separator in between. Empty when there is nothing
     * to show.
     */
    public function format(Offer $offer): string
    {
        return $this->adjustedDaysFormatter->format($offer->getAdjustedDays())
            . $this->closedDaysFormatter->format($offer->getClosedDays());
    }
}
