<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Offer;

/**
 * Renders the periods that deviate from the regular opening hours as plain text:
 * the adjusted days followed by the closed days.
 */
final class PlainTextPeriodsFormatter
{
    private PlainTextAdjustedDaysFormatter $adjustedDaysFormatter;

    private PlainTextClosedDaysFormatter $closedDaysFormatter;

    public function __construct(Translator $translator)
    {
        $this->adjustedDaysFormatter = new PlainTextAdjustedDaysFormatter($translator);
        $this->closedDaysFormatter = new PlainTextClosedDaysFormatter($translator);
    }

    /**
     * The adjusted and closed days get some visual space, both from the opening
     * hours above them and from each other. Empty when there is nothing to show,
     * so the caller does not have to guard the leading blank line itself.
     */
    public function format(Offer $offer): string
    {
        $periods = array_filter([
            $this->adjustedDaysFormatter->format($offer->getAdjustedDays()),
            $this->closedDaysFormatter->format($offer->getClosedDays()),
        ]);

        if (!$periods) {
            return '';
        }

        $separator = PHP_EOL . PHP_EOL;

        return $separator . implode($separator, $periods);
    }
}
