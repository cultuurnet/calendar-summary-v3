<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlStatusFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTimeInterface;

final class SmallSingleHTMLFormatter implements SingleFormatterInterface
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
        $dateFromDay = $this->formatter->formatAsDayNumber($dateFrom);
        $dateFromMonth = $this->formatter->formatAsAbbreviatedMonthName($dateFrom);

        return '<span class="cf-date">' . ucfirst($dateFromDay) . '</span>'
            . ' '
            . '<span class="cf-month">' . $dateFromMonth . '</span>';
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
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">' . $this->translator->translate('till') . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $dateEndDay . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-month">' . $dateEndMonth . '</span>';

        return $output;
    }
}
