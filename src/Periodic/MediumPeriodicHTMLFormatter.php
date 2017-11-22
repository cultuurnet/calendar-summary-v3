<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;
use IntlDateFormatter;

class MediumPeriodicHTMLFormatter implements PeriodicFormatterInterface
{

    public function format($offer) {
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
        $dateFromTimestamp = $dateFrom->getTimestamp();
        $intlDateFrom =$fmt->format($dateFromTimestamp);
        $intlDateFromDay = $fmtDay->format($dateFromTimestamp);

        $dateTo = $offer->getEndDate();
        $dateToTimestamp = $dateTo->getTimestamp();
        $intlDateTo = $fmt->format($dateToTimestamp);

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
