<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\RelativeDateHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeInterface;

final class SmallSingleHTMLFormatter implements SingleFormatterInterface
{
    use RelativeDateHTMLFormatter;
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

        $optionalAvailability = HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
            ->withBraces()
            ->toString();

        return trim($output . ' ' . $optionalAvailability);
    }

    private function formatSameDay(DateTimeInterface $dateFrom): string
    {
        $relativeDate = $this->getRelativeDate($dateFrom, $this->translator, $this->formatter);
        if (isset($relativeDate)) {
            return $relativeDate;
        }

        $dateFromDay = $this->formatter->formatAsDayNumber($dateFrom);
        $dateFromMonth = $this->formatter->formatAsAbbreviatedMonthName($dateFrom);

        $formattedDate = '<span class="cf-date">' . ucfirst($dateFromDay) . '</span>'
            . ' '
            . '<span class="cf-month">' . $dateFromMonth . '</span>';

        if (!DateComparison::isCurrentYear($dateFrom)) {
            $formattedDate .= ' <span class="cf-year">' . $this->formatter->formatAsYear($dateFrom) . '</span>';
        }

        return $formattedDate;
    }

    private function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $dateFromDay = $this->formatter->formatAsDayNumber($dateFrom);
        $dateFromMonth = $this->formatter->formatAsAbbreviatedMonthName($dateFrom);

        $dateEndDay = $this->formatter->formatAsDayNumber($dateEnd);
        $dateEndMonth = $this->formatter->formatAsAbbreviatedMonthName($dateEnd);

        $output = '<span class="cf-from cf-meta">' . ucfirst($this->translator->translate('from')) . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $dateFromDay . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-month">' . $dateFromMonth . '</span>';
        if (!DateComparison::isCurrentYear($dateFrom)) {
            $output .= ' ';
            $output .= '<span class="cf-year">' . $this->formatter->formatAsYear($dateFrom) . '</span>';
        }
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">' . $this->translator->translate('till') . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $dateEndDay . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-month">' . $dateEndMonth . '</span>';
        if (!DateComparison::isCurrentYear($dateEnd)) {
            $output .= ' ';
            $output .= '<span class="cf-year">' . $this->formatter->formatAsYear($dateEnd) . '</span>';
        }

        return $output;
    }
}
