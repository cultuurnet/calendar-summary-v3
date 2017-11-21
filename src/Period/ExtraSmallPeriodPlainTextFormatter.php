<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 31/03/15
 * Time: 10:31
 */

namespace CultuurNet\CalendarSummary\Period;

use \CultureFeed_Cdb_Data_Calendar_PeriodList;
use \DateTime;
use \DateTimeInterface;
use \IntlDateFormatter;

class ExtraSmallPeriodPlainTextFormatter implements PeriodFormatterInterface
{
    private $fmtDay;

    private $fmtMonth;

    public function __construct()
    {
        $this->fmtDay = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd'
        );

        $this->fmtMonth = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'M'
        );
    }

    public function format(
        \CultureFeed_Cdb_Data_Calendar_PeriodList $periodList
    ) {
        $period = $periodList->current();
        $startDate = $this->dateFromString($period->getDateFrom());
        $startDate->setTime(0, 0, 1);

        $now = new DateTime();

        if ($startDate > $now) {
            return $this->formatNotStarted($startDate);
        } else {
            $endDate = $this->dateFromString($period->getDateTo());
            return $this->formatStarted($endDate);
        }
    }

    /**
     * @param string $dateString
     * @return DateTime
     */
    private function dateFromString($dateString)
    {
        return DateTime::createFromFormat('Y-m-d', $dateString);
    }

    /**
     * @param DateTimeInterface $endDate
     * @return string
     */
    private function formatStarted(DateTimeInterface $endDate)
    {
        return 'Tot ' . $this->formatDate($endDate);
    }

    /**
     * @param DateTimeInterface $startDate
     * @return string
     */
    private function formatNotStarted(DateTimeInterface $startDate)
    {
        return 'Vanaf ' . $this->formatDate($startDate);
    }

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    private function formatDate(DateTimeInterface $date)
    {
        $dateFromDay = $this->fmtDay->format($date);
        $dateFromMonth = $this->fmtMonth->format($date);

        $output = $dateFromDay . '/' . $dateFromMonth;

        return $output;
    }
}
