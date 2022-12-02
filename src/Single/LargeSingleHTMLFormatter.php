<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeInterface;

final class LargeSingleHTMLFormatter implements SingleFormatterInterface
{
    /**
     * @var DateFormatter
     */
    private $formatter;

    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->formatter = new DateFormatter($translator->getLocale());
        $this->translator = $translator;
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

        $optionalAvailability = HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
            ->withBraces()
            ->toString();

        return trim($output . ' ' . $optionalAvailability);
    }

    private function formatSameDay(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlWeekDayFrom = $this->formatter->formatAsDayOfWeek($dateFrom);
        $intlStartTimeFrom = $this->formatter->formatAsTime($dateFrom);
        $intlEndTimeEnd = $this->formatter->formatAsTime($dateEnd);

        if ($intlStartTimeFrom === '00:00' && $intlEndTimeEnd === '23:59') {
            return '<time itemprop="startDate" datetime="' . $dateFrom->format(\DateTime::ATOM) . '">'
                . '<span class="cf-weekday cf-meta">' . ucfirst($intlWeekDayFrom) . '</span>'
                . ' '
                . '<span class="cf-date">' . $intlDateFrom . '</span>'
                . '</time>';
        }

        if ($intlStartTimeFrom == $intlEndTimeEnd) {
            return '<time itemprop="startDate" datetime="' . $dateFrom->format(\DateTime::ATOM) . '">'
                . '<span class="cf-weekday cf-meta">' . ucfirst($intlWeekDayFrom) . '</span>'
                . ' '
                . '<span class="cf-date">' . $intlDateFrom . '</span>'
                . ' '
                . '<span class="cf-from cf-meta">' . $this->translator->translate('at') . '</span>'
                . ' '
                . '<span class="cf-time">' . $intlStartTimeFrom . '</span>'
                . '</time>';
        }

        return '<time itemprop="startDate" datetime="' . $dateFrom->format(\DateTime::ATOM) . '">'
            . '<span class="cf-weekday cf-meta">' . ucfirst($intlWeekDayFrom) . '</span>'
            . ' '
            . '<span class="cf-date">' . $intlDateFrom . '</span>'
            . ' '
            . '<span class="cf-from cf-meta">' . $this->translator->translate('from_hour') . '</span>'
            . ' '
            . '<span class="cf-time">' . $intlStartTimeFrom . '</span>'
            . '</time>'
            . ' '
            . '<span class="cf-to cf-meta">' . $this->translator->translate('till_hour') . '</span>'
            . ' '
            . '<time itemprop="endDate" datetime="' . $dateEnd->format(\DateTime::ATOM) . '">'
            . '<span class="cf-time">' . $intlEndTimeEnd . '</span>'
            . '</time>';
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
        $output .= '<span class="cf-from cf-meta">' . ucfirst($this->translator->translate('from')) . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-weekday cf-meta">' . $intlWeekDayFrom . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-at cf-meta">' . $this->translator->translate('at') . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">' . $intlStartTimeFrom . '</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">' . $this->translator->translate('till_large') . '</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="' . $dateEnd->format(\DateTime::ATOM) . '">';
        $output .= '<span class="cf-weekday cf-meta">' . $intlWeekDayEnd . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDateEnd . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-at cf-meta">' . $this->translator->translate('at') . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">' . $intlEndTimeEnd . '</span>';
        $output .= '</time>';

        return $output;
    }
}
