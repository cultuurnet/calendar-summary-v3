<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\IntlDateFormatterFactory;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use \DateTime;
use \DateTimeInterface;
use IntlDateFormatter;

final class SmallPeriodicPlainTextFormatter implements PeriodicFormatterInterface
{
    /**
     * @var IntlDateFormatter
     */
    private $fmtDay;

    /**
     * @var IntlDateFormatter
     */
    private $fmtMonth;

    /**
     * @var Translator
     */
    private $trans;

    public function __construct(string $langCode)
    {
        $this->fmtMonth = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'MMM'
        );
        $this->fmtDay = IntlDateFormatterFactory::createDayNumberFormatter($langCode);

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
        $dateFromDay = $this->fmtDay->format($date);
        $dateFromMonth = $this->fmtMonth->format($date);
        $dateFromMonth = rtrim($dateFromMonth, ".");
        $dateFromYear = $date->format('Y');

        $output = $dateFromDay . ' ' . strtolower($dateFromMonth) . ' ' . $dateFromYear;

        return $output;
    }
}
