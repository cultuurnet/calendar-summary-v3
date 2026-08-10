<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\AdjustedDay;
use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\ClosedDay;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHoursPeriod;
use CultuurNet\CalendarSummaryV3\Permanent\MediumPermanentWeekScheme;

/**
 * Renders the periods during which the regular opening hours do not apply
 * as collapsible lists.
 */
final class HtmlOpeningHoursExceptionsFormatter
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
    public function formatAdjustedDays(array $adjustedDays): string
    {
        $upcomingAdjustedDays = $this->withoutPastPeriods($adjustedDays);

        if (!$upcomingAdjustedDays) {
            return '';
        }

        $output = $this->openDetails('cf-adjusted-days', 'except_during');

        foreach ($upcomingAdjustedDays as $adjustedDay) {
            $output .= $this->generatePeriod($adjustedDay, $adjustedDay->getOpeningHours());
        }

        return $output . '</ul></details>';
    }

    /**
     * @param ClosedDay[] $closedDays
     */
    public function formatClosedDays(array $closedDays): string
    {
        $upcomingClosedDays = $this->withoutPastPeriods($closedDays);

        if (!$upcomingClosedDays) {
            return '';
        }

        $output = $this->openDetails('cf-closed-days', 'closed');

        foreach ($upcomingClosedDays as $closedDay) {
            $output .= $this->generatePeriod($closedDay);
        }

        return $output . '</ul></details>';
    }

    /**
     * @template T of OpeningHoursPeriod
     * @param T[] $periods
     * @return T[]
     */
    private function withoutPastPeriods(array $periods): array
    {
        return array_filter($periods, static function (OpeningHoursPeriod $period): bool {
            return !DateComparison::isPastDay($period->getEndDate());
        });
    }

    private function openDetails(string $class, string $summaryTranslationKey): string
    {
        return '<details class="' . $class . '">'
            . '<summary>' . ucfirst($this->translator->translate($summaryTranslationKey)) . '</summary>'
            . '<ul class="list-unstyled">';
    }

    /**
     * @param OpeningHour[] $openingHours
     */
    private function generatePeriod(OpeningHoursPeriod $period, array $openingHours = []): string
    {
        $startDate = $period->getStartDate();
        $endDate = $period->getEndDate();

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

        // When every day of this period has the same childcare it is mentioned once
        // instead of being repeated after every timespan.
        $sharedChildcare = Childcare::sharedByAll($openingHours);

        foreach ($openingHours as $openingHour) {
            $output .= $this->generateOpeningHours($openingHour, $sharedChildcare === null);
        }

        if ($sharedChildcare !== null) {
            $output .= '<span class="cf-childcare">'
                . ChildcareFormatter::forChildcare($sharedChildcare, $this->translator)
                    ->forEveryDay()
                    ->capitalize()
                    ->toString()
                . '</span>';
        }

        $description = $period->getDescriptionForLanguage($this->translator->getLanguageCode());
        if ($description !== '') {
            $output .= '<span class="cf-description">' . $description . '</span>';
        }

        return $output . '</li>';
    }

    private function generateOpeningHours(OpeningHour $openingHours, bool $withChildcare): string
    {
        $opens = OpeningHourFormatter::format($openingHours->getOpens());
        $closes = OpeningHourFormatter::format($openingHours->getCloses());
        $childcare = $openingHours->getChildcare();

        $output = '<span class="cf-days">' . $this->generateDaysOfWeek($openingHours->getDaysOfWeek()) . '</span>'
            . '<span class="cf-from cf-meta">' . $this->translator->translate('from_hour') . '</span>'
            . '<span class="cf-time">' . $opens . '</span>'
            . '<span class="cf-to cf-meta">' . $this->translator->translate('till_hour') . '</span>'
            . '<span class="cf-time">' . $closes . '</span>';

        if ($withChildcare && $childcare !== null) {
            $output .= '<span class="cf-childcare">'
                . ChildcareFormatter::forChildcare($childcare, $this->translator)
                    ->withBraces()
                    ->toString()
                . '</span>';
        }

        return $output;
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
