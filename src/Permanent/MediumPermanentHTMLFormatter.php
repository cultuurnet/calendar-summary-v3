<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeImmutable;

final class MediumPermanentHTMLFormatter implements PermanentFormatterInterface
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
        if (!$offer->isAvailable()) {
            return HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
                ->withElement('p')
                ->withoutBraces()
                ->capitalize()
                ->toString();
        }

        if ($offer->getOpeningHours()) {
            return $this->generateWeekScheme($offer->getOpeningHours());
        }

        return '<p class="cf-openinghours">' .
            ucfirst($this->translator->translate('open_every_day')) . '</p>';
    }

    /**
     * Generate a weekscheme based on the given opening hours.
     *
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
            return '<p class="cf-openinghours">' .
                ucfirst($this->translator->translate('open_every_day')) . '</p>';
        }

        if (count($weekDaysOpen) === 1) {
            return '<p class="cf-openinghours">Elke ' . $this->formatter->formatAsDayOfWeek(new DateTimeImmutable($weekDaysOpen[key($weekDaysOpen)])) . ' open</p>';
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

        $outputWeek = '<span>' . ucfirst($this->translator->translate('open')) . ' '
            . '<span class="cf-weekdays">';

        $i = 0;

        foreach ($translatedDayNamesWithOpeningHours as $translatedDayNamesWithOpeningHour) {
            $outputWeek .= '<span class="cf-weekday-open">' . $translatedDayNamesWithOpeningHour . '</span>';
            if (++$i !== count($translatedDayNamesWithOpeningHours)) {
                $outputWeek .= ' & ';
            }
        }

        $outputWeek .= '</span></span>';

        return $outputWeek;
    }
}
