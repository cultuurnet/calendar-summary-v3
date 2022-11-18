<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Translator;

final class ExtraSmallMultipleHTMLFormatter implements MultipleFormatterInterface
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
        $dateTo = $offer->getEndDate();

        if (DateComparison::onSameDay($dateFrom, $dateTo)) {
            $output = '<span class="cf-date">' . $this->formatter->formatAsDayNumber($dateFrom) . '</span> ' .
                '<span class="cf-month">' . $this->formatter->formatAsAbbreviatedMonthName($dateFrom) . '</span>';
            if (!DateComparison::isCurrentYear($dateFrom)) {
                $output .= ' <span class="cf-year">' . $this->formatter->formatAsYear($dateFrom) . '</span>';
            }
        } else {
            $output = '<span class="cf-date">' . $this->formatter->formatAsDayNumber($dateFrom) .
                '</span> <span class="cf-month">' . $this->formatter->formatAsAbbreviatedMonthName($dateFrom) . '</span>' .
                ' <span class="cf-year">' . $this->formatter->formatAsYear($dateFrom) . '</span>' .
                ' - ' .
                '<span class="cf-date">' . $this->formatter->formatAsDayNumber($dateTo) .
                '</span> <span class="cf-month">' . $this->formatter->formatAsAbbreviatedMonthName($dateTo) . '</span>';
            if (!DateComparison::isCurrentYear($dateTo)) {
                $output .= ' <span class="cf-year">' . $this->formatter->formatAsYear($dateTo) . '</span>';
            }
        }

        $optionalAvailability = HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
            ->withBraces()
            ->toString();

        return trim($output . ' ' . $optionalAvailability);
    }
}
