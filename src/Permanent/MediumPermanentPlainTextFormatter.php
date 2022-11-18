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
        $weekDaysOpen = [];
        // Create a list of all day names that have opening hours
        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDaysOfWeek() as $dayName) {
                if (!in_array($dayName, $weekDaysOpen, true)) {
                    $weekDaysOpen[(int) $this->formatter->formatAsDayOfWeekNumber(new DateTimeImmutable($dayName))] = $dayName;
                }
            }
        }

        if (count($weekDaysOpen) === 7) {
            return PlainTextSummaryBuilder::start($this->translator)
                ->alwaysOpen()
                ->startNewLine()
                ->toString();
        }

        if (count($weekDaysOpen) === 1) {
            return 'Elke ' . $this->formatter->formatAsDayOfWeek(new DateTimeImmutable($weekDaysOpen[key($weekDaysOpen)])) . ' open';
        }

        $translatedDayNamesWithOpeningHours = [];
        $dayPeriod = '';
        $startNewPeriod = true;
        foreach ($weekDaysOpen as $weekDayNumber => $dayName) {
            // We start a new period, but the following day is closed
            if ($startNewPeriod && !array_key_exists($weekDayNumber + 1, $weekDaysOpen)) {
                $translatedDayNamesWithOpeningHours[] = $this->formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($dayName));
            }
            // Start a new period and the following day is open
            if ($startNewPeriod && array_key_exists($weekDayNumber + 1, $weekDaysOpen)) {
                $dayPeriod = $this->formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($dayName));
                $startNewPeriod = false;
            }
            // The previous day was open but the following day isn't
            if (!$startNewPeriod && !array_key_exists($weekDayNumber + 1, $weekDaysOpen)) {
                $dayPeriod .= ' - ' . $this->formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($dayName));
                $translatedDayNamesWithOpeningHours[] = $dayPeriod;
                $startNewPeriod = true;
                $dayPeriod = '';
            }
            // Do nothing if both the previous & following day are open
        }

        // Put all the day names with opening hours on a single line with 'Open at' (sec) at the beginning.
        // E.g. 'Open at monday - thursday & sunday'
        return PlainTextSummaryBuilder::start($this->translator)
            ->openAt(...$translatedDayNamesWithOpeningHours)
            ->toString();
    }
}
