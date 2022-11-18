<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\RelativeDateHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeInterface;

final class SmallMultipleHTMLFormatter implements MultipleFormatterInterface
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
        $dateFrom = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $intlDateFrom = $this->formatter->formatAsDayNumber($dateFrom) .
            ' ' . $this->formatter->formatAsAbbreviatedMonthName($dateFrom);
        if (!DateComparison::isCurrentYear($dateFrom)) {
            $intlDateFrom .= ' ' . $this->formatter->formatAsYear($dateFrom);
        }

        $dateTo = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $intlDateTo = $this->formatter->formatAsDayNumber($dateTo) .
            ' ' . $this->formatter->formatAsAbbreviatedMonthName($dateTo);
        if (!DateComparison::isCurrentYear($dateTo)) {
            $intlDateTo .= ' ' . $this->formatter->formatAsYear($dateTo);
        }

        if (DateComparison::onSameDay($dateFrom, $dateTo)) {
            $output = $this->formatSameDay($dateFrom, $intlDateFrom);
        } else {
            $output = '<span class="cf-date">' . $intlDateFrom . '</span> '
                . '<span class="cf-to cf-meta">-</span> ' .
                '<span class="cf-date">' . $intlDateTo . '</span>';
        }

        $optionalAvailability = HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
            ->withBraces()
            ->toString();

        return trim($output . ' ' . $optionalAvailability);
    }

    private function formatSameDay(DateTimeInterface $dateFrom, string $intlDateFrom): string
    {
        $relativeDate = $this->getRelativeDate($dateFrom, $this->translator, $this->formatter);
        return $relativeDate ?? (
            '<span class="cf-weekday cf-meta">' . ucfirst($this->formatter->formatAsAbbreviatedDayOfWeek($dateFrom)) . '</span>' .
            ' ' .
            '<span class="cf-date">' . $intlDateFrom . '</span>'
        );
    }
}
