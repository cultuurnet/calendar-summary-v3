<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTimeInterface;

class LargeSinglePlainTextFormatter extends LargeSingleFormatter implements SingleFormatterInterface
{
    public function format(Offer $offer): string
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

    protected function formatSameDay(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlWeekDayFrom = $this->fmtWeekDayLong->format($dateFrom);
        $intlStartTimeFrom = $this->fmtTime->format($dateFrom);

        $intlEndTimeEnd = $this->fmtTime->format($dateEnd);

        if ($intlStartTimeFrom === '00:00' && $intlEndTimeEnd === '23:59') {
            $output = $intlWeekDayFrom . ' ' . $intlDateFrom;
        } elseif ($intlStartTimeFrom == $intlEndTimeEnd) {
            $output = $intlWeekDayFrom . ' ' . $intlDateFrom . ' ';
            $output .= $this->trans->getTranslations()->t('at') . ' ' . $intlStartTimeFrom;
        } else {
            $output = $intlWeekDayFrom . ' ' . $intlDateFrom;
            $output .= ' ' . $this->trans->getTranslations()->t('from') . ' ';
            $output .= $intlStartTimeFrom;
            $output .= ' ' . $this->trans->getTranslations()->t('till') . ' ' . $intlEndTimeEnd;
        }

        return $output;
    }

    protected function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
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
