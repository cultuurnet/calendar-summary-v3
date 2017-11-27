<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;
use \DateTime;
use \DateTimeInterface;
use \IntlDateFormatter;

class ExtraSmallPeriodicPlainTextFormatter implements PeriodicFormatterInterface
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

    public function format($offer)
    {
        $startDate = $offer->getStartDate();
        $startDate->setTime(0, 0, 1);
        $now = new DateTime();

        if ($startDate > $now) {
            return $this->formatNotStarted($startDate);
        } else {
            $endDate = $offer->getEndDate();
            return $this->formatStarted($endDate);
        }
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
