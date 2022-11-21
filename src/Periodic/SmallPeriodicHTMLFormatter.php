<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeInterface;

final class SmallPeriodicHTMLFormatter implements PeriodicFormatterInterface
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
        $startDate = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $startDate->setTime(0, 0, 1);

        if (DateComparison::inTheFuture($startDate)) {
            $output = $this->formatNotStarted($startDate);
        } else {
            $endDate = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
            $output = $this->formatStarted($endDate);
        }

        $optionalAvailability = HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
            ->withBraces()
            ->toString();

        return trim($output . ' ' . $optionalAvailability);
    }

    private function formatStarted(DateTimeInterface $endDate): string
    {
        return
            '<span class="to meta">' . ucfirst($this->translator->translate('till')) . '</span> ' .
            $this->formatDate($endDate);
    }

    private function formatNotStarted(DateTimeInterface $startDate): string
    {
        return
            '<span class="from meta">' . ucfirst($this->translator->translate('from_period')) . '</span> ' .
            $this->formatDate($startDate);
    }

    private function formatDate(DateTimeInterface $date): string
    {
        $formattedDate = '<span class="cf-days">' . $this->formatter->formatAsAbbreviatedDayOfWeek($date) . '</span> ' .
            '<span class="cf-date">' . $this->formatter->formatAsDayNumber($date) . '</span> ' .
            '<span class="cf-month">' . $this->formatter->formatAsAbbreviatedMonthName($date) . '</span>';

        if (!DateComparison::isCurrentYear($date)) {
            $formattedDate .= ' <span class="cf-year">' . $this->formatter->formatAsYear($date) . '</span>';
        }
        return $formattedDate;
    }
}
