<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\IntlDateFormatterFactory;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTimeInterface;
use IntlDateFormatter;

final class LargeSingleHTMLFormatter implements SingleFormatterInterface
{
    /**
     * @var IntlDateFormatter
     */
    private $fmt;

    /**
     * @var IntlDateFormatter
     */
    private $fmtWeekDayLong;

    /**
     * @var IntlDateFormatter
     */
    private $fmtTime;

    /**
     * @var Translator
     */
    private $trans;

    public function __construct(string $langCode)
    {
        $this->fmt = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );
        $this->fmtWeekDayLong = IntlDateFormatterFactory::createDayOfWeekFormatter($langCode);
        $this->fmtTime = IntlDateFormatterFactory::createTimeFormatter($langCode);

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

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

    private function formatSameDay(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
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
