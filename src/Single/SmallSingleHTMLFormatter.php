<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Offer;

/**
 * Provides a formatter for formatting single events in small html format.
 */
class SmallSingleHTMLFormatter extends SmallSingleFormatter implements SingleFormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format(Offer $offer)
    {
        $dateFrom = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $dateEnd = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        if ($dateFrom->format('Y-m-d') == $dateEnd->format('Y-m-d')) {
            $output = $this->formatSameDay($dateFrom);
        } else {
            $output = $this->formatMoreDays($dateFrom, $dateEnd);
        }

        return $output;
    }

    protected function formatSameDay($dateFrom)
    {
        $dateFromDay = $this->fmtDay->format($dateFrom);
        $dateFromMonth = rtrim($this->fmtMonth->format($dateFrom), '.');

        $output = '<span class="cf-date">' . $dateFromDay . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-month">' . $dateFromMonth . '</span>';

        return $output;
    }

    protected function formatMoreDays($dateFrom, $dateEnd)
    {
        $dateFromDay = $this->fmtDay->format($dateFrom);
        $dateFromMonth = rtrim($this->fmtMonth->format($dateFrom), '.');

        $dateEndDay = $this->fmtDay->format($dateEnd);
        $dateEndMonth = rtrim($this->fmtMonth->format($dateEnd), '.');

        $output = '<span class="cf-from cf-meta">' . ucfirst($this->trans->getTranslations()->t('from')) . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $dateFromDay . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-month">' . $dateFromMonth . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">' . $this->trans->getTranslations()->t('till') . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $dateEndDay . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-month">' . $dateEndMonth . '</span>';

        return $output;
    }
}
