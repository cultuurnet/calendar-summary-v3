<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\OfferFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTimeInterface;

final class LargeSingleHTMLFormatter implements OfferFormatter
{
    /**
     * @var DateFormatter
     */
    private $formatter;

    /**
     * @var Translator
     */
    private $trans;

    public function __construct(string $langCode)
    {
        $this->formatter = new DateFormatter($langCode);

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

    public function format(Offer $offer): string
    {
        $dateFrom = $offer->getStartDate();
        $dateEnd = $offer->getEndDate();

        if (DateComparison::onSameDay($dateFrom, $dateEnd)) {
            $output = $this->formatSameDay($dateFrom, $dateEnd);
        } else {
            $output = $this->formatMoreDays($dateFrom, $dateEnd);
        }

        return $output;
    }

    private function formatSameDay(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlWeekDayFrom = $this->formatter->formatAsDayOfWeek($dateFrom);
        $intlStartTimeFrom = $this->formatter->formatAsTime($dateFrom);
        $intlEndTimeEnd = $this->formatter->formatAsTime($dateEnd);

        if ($intlStartTimeFrom === '00:00' && $intlEndTimeEnd === '23:59') {
            $output = '<time itemprop="startDate" datetime="' . $dateFrom->format(\DateTime::ATOM) . '">';
            $output .= '<span class="cf-weekday cf-meta">' . $intlWeekDayFrom . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';
            $output .= '</time>';
        } elseif ($intlStartTimeFrom == $intlEndTimeEnd) {
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

    private function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlWeekDayFrom = $this->formatter->formatAsDayOfWeek($dateFrom);
        $intlStartTimeFrom = $this->formatter->formatAsTime($dateFrom);

        $intlDateEnd = $this->formatter->formatAsFullDate($dateEnd);
        $intlWeekDayEnd = $this->formatter->formatAsDayOfWeek($dateEnd);
        $intlEndTimeEnd = $this->formatter->formatAsTime($dateEnd);

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
