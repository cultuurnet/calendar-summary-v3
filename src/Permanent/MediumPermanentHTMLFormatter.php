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
    use MediumPermanentWeekScheme;

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
            return '<p class="cf-openinghours">' .
                $this->translator->translate('open_every') . ' ' .
                $this->formatter->formatAsDayOfWeek(new DateTimeImmutable(reset($weekDaysOpen))) . ' ' .
                $this->translator->translate('open_every_end') . '</p>';
        }

        $weekScheme = $this->getWeekScheme($weekDaysOpen, $this->formatter);

        $isFirstPeriodMin3days = array_pop($weekScheme);

        $outputWeek = '<span>';
        if ($isFirstPeriodMin3days) {
            $outputWeek .= ucfirst($this->translator->translate('open'));
        } else {
            $outputWeek .= ucfirst($this->translator->translate('open'));
        }
        $outputWeek .= ' <span class="cf-weekdays">';

        $i = 0;
        foreach ($weekScheme as $translatedDayNamesWithOpeningHour) {
            $outputWeek .= '<span class="cf-weekday-open">' . $translatedDayNamesWithOpeningHour . '</span>';
            if (++$i !== count($weekScheme)) {
                $outputWeek .= ' & ';
            }
        }

        $outputWeek .= '</span></span>';

        return $outputWeek;
    }
}
