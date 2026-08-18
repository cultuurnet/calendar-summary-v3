<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\AdjustedDay;
use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHours;
use CultuurNet\CalendarSummaryV3\Offer\Period;
use CultuurNet\CalendarSummaryV3\Permanent\MediumPermanentWeekScheme;

/**
 * Renders the periods during which the regular opening hours are replaced by
 * other opening hours as plain text.
 */
final class PlainTextAdjustedDaysFormatter
{
    use MediumPermanentWeekScheme;

    private DateFormatter $formatter;

    private Translator $translator;

    private PlainTextPeriodListFormatter $periodListFormatter;

    public function __construct(Translator $translator)
    {
        $this->formatter = new DateFormatter($translator->getLocale());
        $this->translator = $translator;
        $this->periodListFormatter = new PlainTextPeriodListFormatter($translator);
    }

    /**
     * @param AdjustedDay[] $adjustedDays
     */
    public function format(array $adjustedDays): string
    {
        return $this->periodListFormatter->format(
            $adjustedDays,
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

        $lines = [];
        foreach ($openingHours as $openingHour) {
            $lines[] = $this->generateOpeningHours($openingHour, $sharedChildcare === null);
        }

        if ($sharedChildcare !== null) {
            $lines[] = $this->generateChildcare($sharedChildcare, true);
        }

        return implode(PHP_EOL, $lines);
    }

    private function generateOpeningHours(OpeningHour $openingHour, bool $withChildcare): string
    {
        $lines = [
            PlainTextSummaryBuilder::start($this->translator)
                ->append($this->generateDaysOfWeek($openingHour->getDaysOfWeek()))
                ->fromHour(OpeningHourFormatter::format($openingHour->getOpens()))
                ->tillHour(OpeningHourFormatter::format($openingHour->getCloses()))
                ->toString(),
        ];

        $childcare = $openingHour->getChildcare();
        if ($withChildcare && $childcare !== null) {
            $lines[] = $this->generateChildcare($childcare);
        }

        return implode(PHP_EOL, $lines);
    }

    /**
     * Childcare gets a line of its own, indented with a single space and between braces.
     */
    private function generateChildcare(Childcare $childcare, bool $forEveryDay = false): string
    {
        $childcareFormatter = ChildcareFormatter::forChildcare($childcare, $this->translator)->withBraces();

        if ($forEveryDay) {
            $childcareFormatter = $childcareFormatter->forEveryDay();
        }

        return ' ' . $childcareFormatter->toString();
    }

    /**
     * Collapses consecutive days into a single period, e.g. 'maandag - donderdag'.
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

        return implode(', ', $this->getWeekScheme($weekDaysOpen, $this->formatter, false));
    }
}
