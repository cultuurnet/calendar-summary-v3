<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use \DateTime;
use \DateTimeInterface;

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
            return $this->formatNotStarted($startDate);
        }

        $endDate = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        return $this->formatStarted($endDate);
    }

    private function formatStarted(DateTimeInterface $endDate): string
    {
        return
            '<span class="to meta">' . ucfirst($this->translator->getTranslations()->t('till')) . '</span> ' .
            $this->formatDate($endDate);
    }

    private function formatNotStarted(DateTimeInterface $startDate): string
    {
        return
            '<span class="from meta">' . ucfirst($this->translator->getTranslations()->t('from_period')) . '</span> ' .
            $this->formatDate($startDate);
    }

    private function formatDate(DateTimeInterface $date): string
    {
        $dateFromDay = $this->formatter->formatAsDayNumber($date);
        $dateFromMonth = $this->formatter->formatAsAbbreviatedMonthName($date);
        $dateFromYear = $this->formatter->formatAsYear($date);

        return
            '<span class="cf-date">' . $dateFromDay . '</span> ' .
            '<span class="cf-month">' . $dateFromMonth . '</span> ' .
            '<span class="cf-year">' . $dateFromYear . '</span>';
    }
}
