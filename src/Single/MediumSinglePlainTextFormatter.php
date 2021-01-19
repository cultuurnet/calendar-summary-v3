<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTimeInterface;
use IntlDateFormatter;

final class MediumSinglePlainTextFormatter implements SingleFormatterInterface
{
    /**
     * @var IntlDateFormatter
     */
    private $fmt;

    /**
     * @var IntlDateFormatter
     */
    private $fmtDay;

    /**
     * @var Translator
     */
    private $trans;

    public function __construct(string $langCode)
    {
        $this->fmt = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );

        $this->fmtDay = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'eeee'
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
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlDateDayFrom = $this->fmtDay->format($dateFrom);

        $output = $intlDateDayFrom . ' ' . $intlDateFrom;

        return $output;
    }

    protected function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlDateDayFrom = $this->fmtDay->format($dateFrom);

        $intlDateEnd = $this->fmt->format($dateEnd);
        $intlDateDayEnd = $this->fmtDay->format($dateEnd);

        $output = $this->trans->getTranslations()->t('from') . ' ' . $intlDateDayFrom . ' '
            . $intlDateFrom . ' ' . $this->trans->getTranslations()->t('till') . ' '
            . $intlDateDayEnd . ' ' . $intlDateEnd;

        return $output;
    }
}
