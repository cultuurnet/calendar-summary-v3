<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\AdjustedDay;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHours;
use CultuurNet\CalendarSummaryV3\Offer\Period;
use CultuurNet\CalendarSummaryV3\Permanent\MediumPermanentWeekScheme;

/**
 * Renders the periods during which the regular opening hours are replaced by
 * other opening hours as a collapsible list.
 */
final class HtmlAdjustedDaysFormatter
{
    use MediumPermanentWeekScheme;

    private DateFormatter $formatter;

    private Translator $translator;

    private HtmlPeriodListFormatter $periodListFormatter;

    public function __construct(Translator $translator)
    {
        $this->formatter = new DateFormatter($translator->getLocale());
        $this->translator = $translator;
        $this->periodListFormatter = new HtmlPeriodListFormatter($translator);
    }

    /**
     * @param AdjustedDay[] $adjustedDays
     */
    public function format(array $adjustedDays): string
    {
        return $this->periodListFormatter->format(
            $adjustedDays,
            'cf-adjusted-days',
            'except_during',
            function (Period $adjustedDay): string {
                return $adjustedDay instanceof AdjustedDay
                    ? $this->generateOpeningHoursOfPeriod($adjustedDay->getOpeningHours())
                    : '';
            }
        );
    }

    private function generateOpeningHoursOfPeriod(OpeningHours $openingHours): string
    {
        // When every day of this period has the same childcare it is mentioned once
        // instead of being repeated after every timespan.
        $sharedChildcare = $openingHours->sharedChildcare();

        $output = '';
        foreach ($openingHours as $openingHour) {
            $output .= '<li>' . $this->generateOpeningHours($openingHour, $sharedChildcare === null) . '</li>';
        }

        if ($output !== '') {
            // Every group of days gets its own line, like the week scheme of the regular opening hours.
            $output = '<ul class="list-unstyled">' . $output . '</ul>';
        }

        if ($sharedChildcare !== null) {
            $output .= '<span class="cf-childcare">'
                . ChildcareFormatter::forChildcare($sharedChildcare, $this->translator)
                    ->forEveryDay()
                    ->capitalize()
                    ->toString()
                . '</span>';
        }

        return $output;
    }

    private function generateOpeningHours(OpeningHour $openingHour, bool $withChildcare): string
    {
        $opens = OpeningHourFormatter::format($openingHour->getOpens());
        $closes = OpeningHourFormatter::format($openingHour->getCloses());
        $childcare = $openingHour->getChildcare();

        $output = '<span class="cf-days">' . $this->generateDaysOfWeek($openingHour->getDaysOfWeek()) . '</span>'
            . '<span class="cf-from cf-meta">' . $this->translator->translate('from_hour') . '</span>'
            . '<span class="cf-time">' . $opens . '</span>'
            . '<span class="cf-to cf-meta">' . $this->translator->translate('till_hour') . '</span>'
            . '<span class="cf-time">' . $closes . '</span>';

        if ($withChildcare && $childcare !== null) {
            $output .= '<span class="cf-childcare">'
                . ChildcareFormatter::forChildcare($childcare, $this->translator)
                    ->withBraces()
                    ->capitalize()
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
