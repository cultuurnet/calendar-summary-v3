<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use \DateTime;
use \DateTimeInterface;
use \IntlDateFormatter;

/**
 * Provide a small HTML formatter for periodic calendar type.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class SmallPeriodicHTMLFormatter implements PeriodicFormatterInterface
{
    /**
     * @var IntlDateFormatter
     */
    private $fmtDay;

    /**
     * @var IntlDateFormatter
     */
    private $fmtMonth;

    /**
     * SmallPeriodicHTMLFormatter constructor.
     */
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
            'MMM'
        );
    }

    /**
     * Return formatted period string.
     *
     * @param \CultuurNet\SearchV3\ValueObjects\Offer|\CultuurNet\SearchV3\ValueObjects\Place $offer
     * @return string
     */
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
        return
            '<span class="to meta">Tot</span> ' .
            $this->formatDate($endDate);
    }

    /**
     * @param DateTimeInterface $startDate
     * @return string
     */
    private function formatNotStarted(DateTimeInterface $startDate)
    {
        return
            '<span class="from meta">Vanaf</span> ' .
            $this->formatDate($startDate);
    }

    /**
     * @param DateTimeInterface $date
     * @return string
     */
    private function formatDate(DateTimeInterface $date)
    {
        $dateFromDay = $this->fmtDay->format($date);
        $dateFromMonth = $this->fmtMonth->format($date);
        $dateFromMonth = rtrim($dateFromMonth, ".");

        $output =
            '<span class="cf-date">' . $dateFromDay . '</span> ' .
            '<span class="cf-month">' . strtolower($dateFromMonth) . '</span>';

        return $output;
    }
}
