<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;
use IntlDateFormatter;

class MediumPeriodicPlainTextFormatter implements PeriodicFormatterInterface
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
            $output = $intlDateFromDay . ' ' . $intlDateFrom;
        } else {
            $output = 'Van ' . $intlDateFrom . ' tot '. $intlDateTo;
        }

        return $output;
    }
}
