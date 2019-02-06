<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Offer;

/**
 * Provides a formatter for formatting single events in large html format.
 */
class LargeSingleHTMLFormatter extends LargeSingleFormatter implements SingleFormatterInterface
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
            $output = '<time itemprop="startDate" datetime="' . $dateFrom->format(\DateTime::ATOM) . '">';
            $output .= '<span class="cf-weekday cf-meta">' . $intlWeekDayFrom . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';
            $output .= '</time>';
        } else if ($intlStartTimeFrom == $intlEndTimeEnd) {
            $output = '<time itemprop="startDate" datetime="' . $dateFrom->format(\DateTime::ATOM) . '">';
            $output .= '<span class="cf-weekday cf-meta">' . $intlWeekDayFrom . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-from cf-meta">' . $this->trans->getTranslations()->t('at') . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-time">' . $intlStartTimeFrom . '</span>';
            $output .= '</time>';
        } else {
            $output = '<time itemprop="startDate" datetime="' . $dateFrom->format(\DateTime::ATOM) . '">';
            $output .= '<span class="cf-weekday cf-meta">' . $intlWeekDayFrom . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-from cf-meta">' . $this->trans->getTranslations()->t('from') . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-time">' . $intlStartTimeFrom . '</span>';
            $output .= '</time>';
            $output .= ' ';
            $output .= '<span class="cf-to cf-meta">' . $this->trans->getTranslations()->t('till') . '</span>';
            $output .= ' ';
            $output .= '<time itemprop="endDate" datetime="' . $dateEnd->format(\DateTime::ATOM) . '">';
            $output .= '<span class="cf-time">' . $intlEndTimeEnd . '</span>';
            $output .= '</time>';
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

        $output = '<time itemprop="startDate" datetime="' . $dateFrom->format(\DateTime::ATOM) . '">';
        $output .= '<span class="cf-from cf-meta">' . ucfirst($this->trans->getTranslations()->t('from')) . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-weekday cf-meta">' . $intlWeekDayFrom . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-at cf-meta">' . $this->trans->getTranslations()->t('at') . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">' . $intlStartTimeFrom . '</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">' . $this->trans->getTranslations()->t('till') . '</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="' . $dateEnd->format(\DateTime::ATOM) . '">';
        $output .= '<span class="cf-weekday cf-meta">' . $intlWeekDayEnd . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDateEnd . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-at cf-meta">' . $this->trans->getTranslations()->t('at') . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">' . $intlEndTimeEnd . '</span>';
        $output .= '</time>';

        return $output;
    }
}
