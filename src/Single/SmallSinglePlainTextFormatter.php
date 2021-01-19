<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTimeInterface;
use IntlDateFormatter;

final class SmallSinglePlainTextFormatter implements SingleFormatterInterface
{
    /**
     * @var IntlDateFormatter
     */
    protected $fmtDay;

    /**
     * @var IntlDateFormatter
     */
    protected $fmtMonth;

    /**
     * @var Translator
     */
    private $trans;

    public function __construct(string $langCode)
    {
        $this->fmtDay = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd'
        );

        $this->fmtMonth = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'MMM'
        );

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

    public function format(Offer $offer): string
    {
        $dateFrom = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $dateEnd = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        if ($dateFrom->format('Y-m-d') == $dateEnd->format('Y-m-d')) {
            $output = $this->formatSameDay($dateFrom);
        } else {
            $output = $this->formatMoreDays($dateFrom, $dateEnd);
        }

        return $output;
    }

    protected function formatSameDay(DateTimeInterface $dateFrom): string
    {
        $dateFromDay = $this->fmtDay->format($dateFrom);
        $dateFromMonth = rtrim($this->fmtMonth->format($dateFrom), '.');

        $output = $dateFromDay . ' ' . $dateFromMonth;

        return $output;
    }

    protected function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $dateFromDay = $this->fmtDay->format($dateFrom);
        $dateFromMonth = rtrim($this->fmtMonth->format($dateFrom), '.');

        $dateEndDay = $this->fmtDay->format($dateEnd);
        $dateEndMonth = rtrim($this->fmtMonth->format($dateEnd), '.');

        $output = $this->trans->getTranslations()->t('from') . ' ' . $dateFromDay . ' '
            . $dateFromMonth . ' ' . $this->trans->getTranslations()->t('till') . ' '
            . $dateEndDay . ' ' . $dateEndMonth;

        return $output;
    }
}
