<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\OfferFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use \DateTime;
use \DateTimeInterface;

final class SmallPeriodicPlainTextFormatter implements OfferFormatter
{
    /**
     * @var DateFormatter
     */
    private $formatter;

    /**
     * @var Translator
     */
    private $trans;

    public function __construct(string $langCode)
    {
        $this->formatter = new DateFormatter($langCode);

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

    public function format(Offer $offer): string
    {
        $startDate = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $startDate->setTime(0, 0, 1);
        $now = new DateTime();

        if ($startDate > $now) {
            return $this->formatNotStarted($startDate);
        } else {
            $endDate = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
            return $this->formatStarted($endDate);
        }
    }

    private function formatStarted(DateTimeInterface $endDate): string
    {
        return ucfirst($this->trans->getTranslations()->t('till')) . ' ' . $this->formatDate($endDate);
    }

    private function formatNotStarted(DateTimeInterface $startDate): string
    {
        return ucfirst($this->trans->getTranslations()->t('from_period')) . ' ' . $this->formatDate($startDate);
    }

    private function formatDate(DateTimeInterface $date): string
    {
        $dateFromDay = $this->formatter->formatAsDayNumber($date);
        $dateFromMonth = $this->formatter->formatAsAbbreviatedMonthName($date);
        $dateFromYear = $this->formatter->formatAsYear($date);

        return $dateFromDay . ' ' . $dateFromMonth . ' ' . $dateFromYear;
    }
}
