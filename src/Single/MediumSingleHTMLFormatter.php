<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlStatusFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTimeInterface;

final class MediumSingleHTMLFormatter implements SingleFormatterInterface
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
            $output = $this->formatSameDay($dateFrom);
        } else {
            $output = $this->formatMoreDays($dateFrom, $dateEnd);
        }

        $optionalStatus = HtmlStatusFormatter::forOffer($offer, $this->translator)
            ->withBraces()
            ->toString();

        return trim($output . ' ' . $optionalStatus);
    }

    private function formatSameDay(DateTimeInterface $dateFrom): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlDateDayFrom = $this->formatter->formatAsDayOfWeek($dateFrom);

        $output = '<span class="cf-weekday cf-meta">' . ucfirst($intlDateDayFrom) . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';

        return $output;
    }

    private function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlDateDayFrom = $this->formatter->formatAsDayOfWeek($dateFrom);

        $intlDateEnd = $this->formatter->formatAsFullDate($dateEnd);
        $intlDateDayEnd = $this->formatter->formatAsDayOfWeek($dateEnd);

        $output = '<span class="cf-from cf-meta">' . ucfirst($this->translator->translate('from')) . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-weekday cf-meta">' . $intlDateDayFrom . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">' . $this->translator->translate('till') . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-weekday cf-meta">' . $intlDateDayEnd . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDateEnd . '</span>';

        return $output;
    }
}
