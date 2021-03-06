<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
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
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->formatter = new DateFormatter($translator->getLocale());
        $this->translator = $translator;
    }

    public function format(Offer $offer): string
    {
        if ($offer->getStatus()->getType() === 'Unavailable') {
            return ucfirst($this->translator->translate('cancelled'));
        }

        if ($offer->getStatus()->getType() === 'TemporarilyUnavailable') {
            return ucfirst($this->translator->translate('postponed'));
        }

        if ($offer->getOpeningHours()) {
            return $this->generateWeekScheme($offer->getOpeningHours());
        }

        return PlainTextSummaryBuilder::start($this->translator)
            ->alwaysOpen()
            ->startNewLine()
            ->toString();
    }

    /**
     * @param OpeningHour[] $openingHoursData
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
        return PlainTextSummaryBuilder::start($this->translator)
            ->openAt(...$translatedDayNamesWithOpeningHours)
            ->toString();
    }
}
