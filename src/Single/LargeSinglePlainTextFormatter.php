<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Offer;

/**
 * Provides a formatter for formatting single events in large plain text format.
 */
class LargeSinglePlainTextFormatter extends LargeSingleFormatter implements SingleFormatterInterface
{

    /**
     * {@inheritdoc}
     */
    public function format(Offer $offer)
    {
        $dateFrom = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $dateEnd = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        if ($dateFrom->format('Y-m-d') == $dateEnd->format('Y-m-d')) {
            $output = $this->formatSameDay($dateFrom, $dateEnd);
        } else {
            $output = $this->formatMoreDays($dateFrom, $dateEnd);
        }

        return $output;
    }

    protected function formatSameDay($dateFrom, $dateEnd)
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlWeekDayFrom = $this->fmtWeekDayLong->format($dateFrom);
        $intlStartTimeFrom = $this->fmtTime->format($dateFrom);

        $intlEndTimeEnd = $this->fmtTime->format($dateEnd);

        if ($intlStartTimeFrom === '00:00' && $intlEndTimeEnd === '23:59') {
            $output = $intlWeekDayFrom . ' ' . $intlDateFrom;
        } else {
            $output = $intlWeekDayFrom . ' ' . $intlDateFrom;
            $output .= ' ' . $this->trans->getTranslations()->t('from') . ' ';
            $output .= $intlStartTimeFrom;
            $output .= ' ' . $this->trans->getTranslations()->t('till') . ' ' . $intlEndTimeEnd;
        }

        return $output;
    }

    protected function formatMoreDays($dateFrom, $dateEnd)
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlWeekDayFrom = $this->fmtWeekDayLong->format($dateFrom);
        $intlStartTimeFrom = $this->fmtTime->format($dateFrom);

        $intlDateEnd = $this->fmt->format($dateEnd);
        $intlWeekDayEnd = $this->fmtWeekDayLong->format($dateEnd);
        $intlEndTimeEnd = $this->fmtTime->format($dateEnd);

        $output = ucfirst($this->trans->getTranslations()->t('from')) . ' ';
        $output .= $intlWeekDayFrom . ' ' . $intlDateFrom . ' ' . $intlStartTimeFrom;
        $output .= ' ' . $this->trans->getTranslations()->t('till') . ' ';
        $output .= $intlWeekDayEnd . ' ' . $intlDateEnd . ' ' . $intlEndTimeEnd;

        return $output;
    }
}
