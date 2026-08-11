<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\AdjustedDay;
use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\Period;
use CultuurNet\CalendarSummaryV3\Permanent\MediumPermanentWeekScheme;

/**
 * Renders the periods during which the regular opening hours are replaced by
 * other opening hours as a collapsible list.
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

    /**
     * @var HtmlPeriodListFormatter
     */
    private $periodListFormatter;

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

    /**
     * @param OpeningHour[] $openingHours
     */
    private function generateOpeningHoursOfPeriod(array $openingHours): string
    {
        // When every day of this period has the same childcare it is mentioned once
        // instead of being repeated after every timespan.
        $sharedChildcare = Childcare::sharedByAll($openingHours);

        $output = '';
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

        return $output;
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
