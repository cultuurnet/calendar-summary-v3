<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 06/03/15
 * Time: 14:16
 */

namespace CultuurNet\CalendarSummaryV3\Periodic;

use IntlDateFormatter;

class MediumPeriodicPlainTextFormatter implements PeriodicFormatterInterface
{

    public function format(
        \CultureFeed_Cdb_Data_Calendar_PeriodList $periodList
    ) {
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

        $period = $periodList->current();
        $dateFromString = $period->getDateFrom();
        $dateFrom = strtotime($dateFromString);
        $intlDateFrom =$fmt->format($dateFrom);
        $intlDateFromDay = $fmtDay->format($dateFrom);

        $dateToString = $period->getDateTo();
        $dateTo = strtotime($dateToString);
        $intlDateTo = $fmt->format($dateTo);

        if ($intlDateFrom == $intlDateTo) {
            $output = $intlDateFromDay . ' ' . $intlDateFrom;
        } else {
            $output = 'Van ' . $intlDateFrom . ' tot '. $intlDateTo;
        }

        return $output;
    }
}
