<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Offer;

/**
 * Provides a formatter for formatting single events in medium plain text format.
 */
class MediumSinglePlainTextFormatter extends MediumSingleFormatter implements SingleFormatterInterface
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

        $output = $intlDateDayFrom . ' ' . $intlDateFrom;

        return $output;
    }

    protected function formatMoreDays($dateFrom, $dateEnd)
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlDateDayFrom = $this->fmtDay->format($dateFrom);

        $intlDateEnd = $this->fmt->format($dateEnd);
        $intlDateDayEnd = $this->fmtDay->format($dateEnd);

        $output = $this->trans->getTranslations()->t('from') . ' ' . $intlDateDayFrom . ' ' . $intlDateFrom . ' ' . $this->trans->getTranslations()->t('till') . ' ' . $intlDateDayEnd . ' ' . $intlDateEnd;

        return $output;
    }
}
