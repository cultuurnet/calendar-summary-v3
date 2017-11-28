<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use IntlDateFormatter;

/**
 * Provide a medium HTML formatter for periodic calendar type.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class MediumPeriodicHTMLFormatter implements PeriodicFormatterInterface
{

    /**
     * Return formatted period string.
     *
     * @param \CultuurNet\SearchV3\ValueObjects\Offer|\CultuurNet\SearchV3\ValueObjects\Place $offer
     * @return string
     */
    public function format($offer)
    {
        $fmt = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );

        $fmtDay = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'eeee'
        );

        $dateFrom = $offer->getStartDate();
        $intlDateFrom =$fmt->format($dateFrom);
        $intlDateFromDay = $fmtDay->format($dateFrom);

        $dateTo = $offer->getEndDate();
        $intlDateTo = $fmt->format($dateTo);

        if ($intlDateFrom == $intlDateTo) {
            $output = '<span class="cf-weekday cf-meta">' . $intlDateFromDay . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';
        } else {
            $output = '<span class="cf-from cf-meta">Van</span> <span class="cf-date">' . $intlDateFrom . '</span> ';
            $output .= '<span class="cf-to cf-meta">tot</span> <span class="cf-date">'. $intlDateTo . '</span>';
        }

        return $output;
    }
}
