<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;
use \DateTime;
use \DateTimeInterface;

class SmallPeriodicHTMLFormatter extends SmallPeriodicFormatter implements PeriodicFormatterInterface
{
    public function format(Offer $offer): string
    {
        $startDate = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $startDate->setTime(0, 0, 1);
        $now = new DateTime();

        if ($startDate > $now) {
            return $this->formatNotStarted($startDate);
        } else {
            $endDate = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
            return $this->formatStarted($endDate);
        }
    }

    private function formatStarted(DateTimeInterface $endDate): string
    {
        return
            '<span class="to meta">' . ucfirst($this->trans->getTranslations()->t('till')) . '</span> ' .
            $this->formatDate($endDate);
    }

    private function formatNotStarted(DateTimeInterface $startDate): string
    {
        return
            '<span class="from meta">' . ucfirst($this->trans->getTranslations()->t('from_period')) . '</span> ' .
            $this->formatDate($startDate);
    }

    private function formatDate(DateTimeInterface $date): string
    {
        $dateFromDay = $this->fmtDay->format($date);
        $dateFromMonth = $this->fmtMonth->format($date);
        $dateFromMonth = rtrim($dateFromMonth, ".");
        $dateFromYear = $date->format('Y');

        $output =
            '<span class="cf-date">' . $dateFromDay . '</span> ' .
            '<span class="cf-month">' . strtolower($dateFromMonth) . '</span> ' .
            '<span class="cf-year">' . $dateFromYear . '</span>';

        return $output;
    }
}
