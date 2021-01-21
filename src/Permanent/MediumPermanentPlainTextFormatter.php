<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use DateTimeImmutable;

final class MediumPermanentPlainTextFormatter implements PermanentFormatterInterface
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
        if ($offer->getOpeningHours()) {
            return $this->generateWeekScheme($offer->getOpeningHours());
        }

        return (new PlainTextSummaryBuilder($this->trans))
            ->alwaysOpen()
            ->startNewLine()
            ->toString();
    }

    /**
     * @param OpeningHours[] $openingHoursData
     * @return string
     */
    private function generateWeekScheme(array $openingHoursData): string
    {
        // Create a list of all day names that have opening hours, translated
        $translatedDayNamesWithOpeningHours = [];
        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDaysOfWeek() as $dayName) {
                $translatedDayName = $this->formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($dayName));
                $translatedDayNamesWithOpeningHours[] = $translatedDayName;
            }
        }
        $translatedDayNamesWithOpeningHours = array_unique($translatedDayNamesWithOpeningHours);

        // Put all the day names with opening hours on a single line with 'Open at' (sec) at the beginning.
        // E.g. 'Open at monday, wednesday, thursday'
        return (new PlainTextSummaryBuilder($this->trans))
            ->openAt(...$translatedDayNamesWithOpeningHours)
            ->toString();
    }
}
