<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;

class MediumPeriodicPlainTextFormatter extends MediumPeriodicFormatter implements PeriodicFormatterInterface
{
    public function format(Offer $offer): string
    {
        $dateFrom = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlDateFromDay = $this->fmtDay->format($dateFrom);

        $dateTo = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $intlDateTo = $this->fmt->format($dateTo);

        if ($intlDateFrom == $intlDateTo) {
            $output = $intlDateFromDay . ' ' . $intlDateFrom;
        } else {
            $output = ucfirst($this->trans->getTranslations()->t('from')) . ' '
                . $intlDateFrom . ' ' . $this->trans->getTranslations()->t('till') . ' '. $intlDateTo;
        }

        return $output;
    }
}
