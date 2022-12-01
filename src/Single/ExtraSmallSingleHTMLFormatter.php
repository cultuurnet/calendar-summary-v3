<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeInterface;

final class ExtraSmallSingleHTMLFormatter implements SingleFormatterInterface
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

        $optionalAvailability = HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
            ->withBraces()
            ->toString();

        return trim($output . ' ' . $optionalAvailability);
    }

    private function formatSameDay(DateTimeInterface $dateFrom): string
    {
        $dateFromDay = $this->formatter->formatAsDayNumber($dateFrom);
        $dateFromMonth = $this->formatter->formatAsAbbreviatedMonthName($dateFrom);

        $output = '<span class="cf-date">' . ucfirst($dateFromDay) . '</span>'
            . ' '
            . '<span class="cf-month">' . $dateFromMonth . '</span>';

        if (!DateComparison::isCurrentYear($dateFrom)) {
            $output .= ' <span class="cf-year">' . $this->formatter->formatAsYear($dateFrom) . '</span>';
        }

        return $output;
    }

    private function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $output = $this->getDatePart($dateFrom);
        $output .= ' ';
        $output .= '-';
        $output .= ' ';
        $output .= $this->getDatePart($dateEnd);

        return $output;
    }

    private function getDatePart(DateTimeInterface $date): string
    {
        $output = '<span class="cf-date">' . $this->formatter->formatAsDayNumber($date) . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-month">' . $this->formatter->formatAsAbbreviatedMonthName($date) . '</span>';
        if (!DateComparison::isCurrentYear($date)) {
            $output .= ' ';
            $output .= '<span class="cf-year">' . $this->formatter->formatAsYear($date) . '</span>';
        }
        return $output;
    }
}
