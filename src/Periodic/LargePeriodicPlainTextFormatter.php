<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
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

    private function generateDates(DateTime $startDate, DateTime $endDate): string
    {
        $formattedStartDate = $this->formatter->formatAsFullDate($startDate);
        $formattedEndDate = $this->formatter->formatAsFullDate($endDate);

        return (new PlainTextSummaryBuilder($this->trans))
            ->from($formattedStartDate)
            ->till($formattedEndDate)
            ->toString();
    }

    /**
     * @param OpeningHours[] $openingHoursData
     * @return string
     */
    private function generateWeekScheme(array $openingHoursData): string
    {
        /** @var PlainTextSummaryBuilder[] $formattedDays */
        $formattedDays = [];

        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDaysOfWeek() as $dayName) {
                if (!isset($formattedDays[$dayName])) {
                    $translatedDay = $this->formatter->formatAsDayOfWeek(new DateTimeImmutable($dayName));

                    $formattedDays[$dayName] = (new PlainTextSummaryBuilder($this->trans))
                        ->lowercaseNextFirstCharacter()
                        ->append($translatedDay)
                        ->from($this->getFormattedTime($openingHours->getOpens()))
                        ->till($this->getFormattedTime($openingHours->getCloses()));

                    continue;
                }

                $formattedDays[$dayName] = $formattedDays[$dayName]
                    ->and()
                    ->from($this->getFormattedTime($openingHours->getOpens()))
                    ->till($this->getFormattedTime($openingHours->getCloses()));
            }
        }

        return '(' . implode(', ', $formattedDays) . ')';
    }
}
