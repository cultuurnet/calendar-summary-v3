<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use DateTime;
use DateTimeImmutable;

final class LargePeriodicPlainTextFormatter implements PeriodicFormatterInterface
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
        $output = $this->generateDates(
            $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get())),
            $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()))
        );

        if ($offer->getOpeningHours()) {
            $output .= PHP_EOL . $this->generateWeekScheme($offer->getOpeningHours());
        }

        return $output;
    }

    private function getFormattedTime(string $time): string
    {
        $formattedShortTime = ltrim($time, '0');
        if ($formattedShortTime == ':00') {
            $formattedShortTime = '0:00';
        }
        return $formattedShortTime;
    }

    private function generateDates(DateTime $dateFrom, DateTime $dateTo): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlDateTo = $this->formatter->formatAsFullDate($dateTo);

        return ucfirst($this->trans->getTranslations()->t('from')) . ' '
            . $intlDateFrom . ' ' . $this->trans->getTranslations()->t('till') . ' ' . $intlDateTo;
    }

    /**
     * @param OpeningHours[]
     * @return string
     */
    private function generateWeekScheme(array $openingHoursData): string
    {
        $outputWeek = '(';

        // Create an array with formatted days.
        $formattedDays = [];
        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDaysOfWeek() as $dayOfWeek) {
                $translatedDay = $this->formatter->formatAsDayOfWeek(new DateTimeImmutable($dayOfWeek));

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
