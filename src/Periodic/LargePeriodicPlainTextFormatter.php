<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use DateTime;
use IntlDateFormatter;

final class LargePeriodicPlainTextFormatter implements PeriodicFormatterInterface
{
    /**
     * @var IntlDateFormatter
     */
    protected $fmt;

    /**
     * @var IntlDateFormatter
     */
    protected $fmtDays;

    /**
     * @var IntlDateFormatter
     */
    protected $fmtShortDays;

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

        $this->fmtDays = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'EEEE'
        );

        $this->fmtShortDays = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'EEE'
        );

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

    public function format(Offer $offer): string
    {
        $output = $this->generateDates(
            $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get())),
            $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()))
        );

        if ($offer->getOpeningHours()) {
            $output .= PHP_EOL . $this->generateWeekScheme($offer->getOpeningHours());
        }

        return $output;
    }

    protected function getFormattedTime(string $time): string
    {
        $formattedShortTime = ltrim($time, '0');
        if ($formattedShortTime == ':00') {
            $formattedShortTime = '0:00';
        }
        return $formattedShortTime;
    }

    protected function generateDates(DateTime $dateFrom, DateTime $dateTo): string
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlDateTo = $this->fmt->format($dateTo);

        $output_dates =  ucfirst($this->trans->getTranslations()->t('from')) . ' ';
        $output_dates .= $intlDateFrom . ' ' . $this->trans->getTranslations()->t('till') . ' ' . $intlDateTo;
        return $output_dates;
    }

    /**
     * @param OpeningHours[]
     * @return string
     */
    protected function generateWeekScheme(array $openingHoursData): string
    {
        $outputWeek = '(';

        // Create an array with formatted days.
        $formattedDays = [];
        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDaysOfWeek() as $dayOfWeek) {
                $translatedDay = $this->fmtDays->format(strtotime($dayOfWeek));

                if (!isset($formattedDays[$dayOfWeek])) {
                    $formattedDays[$dayOfWeek] = $translatedDay
                        . ' ' . $this->trans->getTranslations()->t('from') . ' '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' ' . $this->trans->getTranslations()->t('till') . ' '
                        . $this->getFormattedTime($openingHours->getCloses());
                } else {
                    $formattedDays[$dayOfWeek] .= ' ' . $this->trans->getTranslations()->t('and') . ' '
                        . $this->trans->getTranslations()->t('from') . ' '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' ' . $this->trans->getTranslations()->t('till') . ' '
                        . $this->getFormattedTime($openingHours->getCloses());
                }
            }
        }

        // Render the rest of the week scheme output.
        foreach ($formattedDays as $formattedDay) {
            $outputWeek .= $formattedDay . ', ';
        }
        $outputWeek = rtrim($outputWeek, ', ' . PHP_EOL);
        return $outputWeek . ')';
    }
}
