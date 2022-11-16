<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeInterface;

final class SmallMultipleHTMLFormatter implements MultipleFormatterInterface
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
        if (DateComparison::isThisEvening($dateFrom)) {
            return '<span class="cf-days">' . $this->translator->translate('tonight') . '</span>';
        }
        if (DateComparison::isToday($dateFrom)) {
            return '<span class="cf-days">' . $this->translator->translate('today') . '</span>';
        }
        if (DateComparison::isTomorrow($dateFrom)) {
            return '<span class="cf-days">' . $this->translator->translate('tomorrow') . '</span>';
        }
        if (DateComparison::isCurrentWeek($dateFrom)) {
            return '<span class="cf-meta">' .
                $this->translator->translate('this') .
                '</span>' .
                ' ' .
                '<span class="cf-days">' .
                $this->formatter->formatAsDayOfWeek($dateFrom) .
                '</span>';
        }

        return '<span class="cf-weekday cf-meta">' . ucfirst($this->formatter->formatAsDayOfWeek($dateFrom)) . '</span>'
            . ' '
            . '<span class="cf-date">' . $intlDateFrom . '</span>';
    }
}
