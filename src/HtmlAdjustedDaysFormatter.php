<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\AdjustedDay;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Permanent\MediumPermanentWeekScheme;

/**
 * Renders the periods with adjusted opening hours as a collapsible list.
 */
final class HtmlAdjustedDaysFormatter
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

    /**
     * @param AdjustedDay[] $adjustedDays
     */
    public function format(array $adjustedDays): string
    {
        $upcomingAdjustedDays = array_filter($adjustedDays, static function (AdjustedDay $adjustedDay): bool {
            return !DateComparison::isPastDay($adjustedDay->getEndDate());
        });

        if (!$upcomingAdjustedDays) {
            return '';
        }

        $output = '<details class="cf-exceptions">'
            . '<summary>' . ucfirst($this->translator->translate('except_during')) . '</summary>'
            . '<ul class="list-unstyled">';

        foreach ($upcomingAdjustedDays as $adjustedDay) {
            $output .= $this->generateAdjustedDay($adjustedDay);
        }

        return $output . '</ul></details>';
    }

    private function generateAdjustedDay(AdjustedDay $adjustedDay): string
    {
        $startDate = $adjustedDay->getStartDate();
        $endDate = $adjustedDay->getEndDate();

        $output = '<li>'
            . '<span class="cf-date">'
            . ucfirst($this->formatter->formatAsDayOfWeek($startDate))
            . ' ' . $this->formatter->formatAsFullDate($startDate)
            . '</span>';

        if (!DateComparison::onSameDay($startDate, $endDate)) {
            $output .= '<span class="cf-to cf-meta">' . $this->translator->translate('till_included') . '</span>'
                . '<span class="cf-date">'
                . $this->formatter->formatAsDayOfWeek($endDate)
                . ' ' . $this->formatter->formatAsFullDate($endDate)
                . '</span>';
        }

        foreach ($adjustedDay->getOpeningHours() as $openingHours) {
            $output .= $this->generateOpeningHours($openingHours);
        }

        $description = $adjustedDay->getDescriptionForLanguage($this->translator->getLanguageCode());
        if ($description !== '') {
            $output .= '<span class="cf-description">' . $description . '</span>';
        }

        return $output . '</li>';
    }

    private function generateOpeningHours(OpeningHour $openingHours): string
    {
        $opens = OpeningHourFormatter::format($openingHours->getOpens());
        $closes = OpeningHourFormatter::format($openingHours->getCloses());

        return '<span class="cf-days">' . $this->generateDaysOfWeek($openingHours->getDaysOfWeek()) . '</span>'
            . '<span class="cf-from cf-meta">' . $this->translator->translate('from_hour') . '</span>'
            . '<span class="cf-time">' . $opens . '</span>'
            . '<span class="cf-to cf-meta">' . $this->translator->translate('till_hour') . '</span>'
            . '<span class="cf-time">' . $closes . '</span>';
    }

    /**
     * Collapses consecutive days into a single period, e.g. 'Maandag - donderdag'.
     *
     * @param string[] $daysOfWeek
     */
    private function generateDaysOfWeek(array $daysOfWeek): string
    {
        // Index by the position in the week instead of by the locale aware day number,
        // because the first day of the week differs per locale.
        $weekDaysOpen = [];
        foreach ($daysOfWeek as $dayOfWeek) {
            $weekDaysOpen[(int) array_search($dayOfWeek, OpeningHour::ALLOWED_DAYS, true)] = $dayOfWeek;
        }

        return ucfirst(implode(', ', $this->getWeekScheme($weekDaysOpen, $this->formatter, false)));
    }
}
