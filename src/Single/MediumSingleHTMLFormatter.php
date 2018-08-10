<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Offer;

/**
 * Provides a formatter for formatting single events in medium html format.
 */
class MediumSingleHTMLFormatter extends MediumSingleFormatter implements SingleFormatterInterface
{

    /**
     * {@inheritdoc}
     */
    public function format(Offer $offer)
    {
        $dateFrom = $offer->getStartDate();
        $dateEnd = $offer->getEndDate();

        if ($dateFrom->format('Y-m-d') == $dateEnd->format('Y-m-d')) {
            $output = $this->formatSameDay($dateFrom);
        } else {
            $output = $this->formatMoreDays($dateFrom, $dateEnd);
        }

        return $output;
    }

    protected function formatSameDay($dateFrom)
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlDateDayFrom = $this->fmtDay->format($dateFrom);

        $output = '<span class="cf-weekday cf-meta">' . $intlDateDayFrom . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';

        return $output;
    }

    protected function formatMoreDays($dateFrom, $dateEnd)
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlDateDayFrom = $this->fmtDay->format($dateFrom);

        $intlDateEnd = $this->fmt->format($dateEnd);
        $intlDateDayEnd = $this->fmtDay->format($dateEnd);

        $output = '<span class="cf-from cf-meta">' . ucfirst($this->trans->t('from')) . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-weekday cf-meta">' . $intlDateDayFrom . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">' . $this->trans->t('till') . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-weekday cf-meta">' . $intlDateDayEnd . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDateEnd . '</span>';

        return $output;
    }
}
