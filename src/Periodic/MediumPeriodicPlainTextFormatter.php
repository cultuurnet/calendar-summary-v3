<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;

/**
 * Provide a medium plain text formatter for periodic calendar type.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class MediumPeriodicPlainTextFormatter extends MediumPeriodicFormatter implements PeriodicFormatterInterface
{

    /**
     * {@inheritdoc}
     */
    public function format(Offer $offer)
    {
        $dateFrom = $offer->getStartDate();
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlDateFromDay = $this->fmtDay->format($dateFrom);

        $dateTo = $offer->getEndDate();
        $intlDateTo = $this->fmt->format($dateTo);

        if ($intlDateFrom == $intlDateTo) {
            $output = $intlDateFromDay . ' ' . $intlDateFrom;
        } else {
            $output = ucfirst($this->trans->getTranslations()->t('from')) . ' ' . $intlDateFrom . ' ' . $this->trans->getTranslations()->t('till') . ' '. $intlDateTo;
        }

        return $output;
    }
}
