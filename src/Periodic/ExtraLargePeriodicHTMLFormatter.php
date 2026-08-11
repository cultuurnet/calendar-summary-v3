<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\ChildcareFormatter;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAdjustedDaysFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\HtmlClosedDaysFormatter;
use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHours;
use CultuurNet\CalendarSummaryV3\OpeningHourFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeImmutable;

final class ExtraLargePeriodicHTMLFormatter implements PeriodicFormatterInterface
{
    /**
     * @var DateFormatter
     */
    private $formatter;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var HtmlAdjustedDaysFormatter
     */
    private $adjustedDaysFormatter;

    /**
     * @var HtmlClosedDaysFormatter
     */
    private $closedDaysFormatter;

    public function __construct(Translator $translator)
    {
        $this->formatter = new DateFormatter($translator->getLocale());
        $this->translator = $translator;
        $this->adjustedDaysFormatter = new HtmlAdjustedDaysFormatter($translator);
        $this->closedDaysFormatter = new HtmlClosedDaysFormatter($translator);
    }

    public function format(Offer $offer): string
    {
        $optionalAvailability = HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
            ->withBraces()
            ->toString();

        $output = $this->generateDates(
            $offer->getStartDate(),
            $offer->getEndDate(),
            $optionalAvailability
        );

        if (!$offer->getOpeningHours()->isEmpty()) {
            $output .= $this->generateWeekScheme($offer->getOpeningHours());
        }

        $output .= $this->adjustedDaysFormatter->format($offer->getAdjustedDays());
        $output .= $this->closedDaysFormatter->format($offer->getClosedDays());

        return trim($this->formatSummary($output));
    }

    private function formatSummary(string $calsum): string
    {
        $calsum = str_replace('><', '> <', $calsum);
        return str_replace('  ', ' ', $calsum);
    }

    /**
     * @return string
     */
    private function generateDates(DateTimeImmutable $dateFrom, DateTimeImmutable $dateTo, string $optionalStatus)
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlDateTo = $this->formatter->formatAsFullDate($dateTo);

        return '<p class="cf-period">'
            . '<span class="cf-weekday cf-meta">' . $this->formatter->formatAsDayOfWeek($dateFrom) . '</span>'
            . '<time itemprop="startDate" datetime="' . $dateFrom->format('Y-m-d') . '">'
            . '<span class="cf-date">' . $intlDateFrom . '</span> </time>'
            . '<span class="cf-to cf-meta">' . $this->translator->translate('to') . '</span>'
            . '<span class="cf-weekday cf-meta">' . $this->formatter->formatAsDayOfWeek($dateTo) . '</span>'
            . '<time itemprop="endDate" datetime="' . $dateTo->format('Y-m-d') . '">'
            . '<span class="cf-date">' . $intlDateTo . '</span> </time>'
            . $optionalStatus
            . '</p>';
    }

    /**
     * @return string
     */
    private function generateWeekScheme(OpeningHours $openingHours)
    {
        $outputWeek = '<p class="cf-openinghours">' . ucfirst($this->translator->translate('open_at')) . ':</p>';
        $outputWeek .= '<ul class="list-unstyled">';

        // Create an array with formatted timespans.
        $formattedTimespans = [];

        // When every day has the same childcare it is summarized in a single list item
        // instead of being repeated on every day.
        $sharedChildcare = $openingHours->sharedChildcare();

        // The childcare of a day is mentioned once after its last timespan, so it is
        // collected while the timespans are rendered.
        $childcarePerDay = [];

        foreach ($openingHours as $openingHour) {
            $daysOfWeek = $openingHour->getDaysOfWeek();
            $firstOpens = OpeningHourFormatter::format($openingHours->earliestTimeOn($daysOfWeek));
            $lastCloses = OpeningHourFormatter::format($openingHours->latestTimeOn($daysOfWeek));
            $opens = OpeningHourFormatter::format($openingHour->getOpens());
            $closes = OpeningHourFormatter::format($openingHour->getCloses());

            foreach ($daysOfWeek as $dayOfWeek) {
                $childcarePerDay[$dayOfWeek] = $sharedChildcare === null
                    ? $openingHours->onDayOfWeek($dayOfWeek)->sharedChildcare()
                    : null;

                // Only when the timespans of this day have a different childcare it is
                // repeated after every single one of them.
                $childcare = $sharedChildcare === null && $childcarePerDay[$dayOfWeek] === null
                    ? $this->generateChildcare($openingHour->getChildcare())
                    : '';

                $daySpanShort = ucfirst(
                    $this->formatter->formatAsAbbreviatedDayOfWeek(
                        new DateTimeImmutable($dayOfWeek)
                    )
                );
                $daySpanLong = ucfirst($this->formatter->formatAsDayOfWeek(new DateTimeImmutable($dayOfWeek)));

                if (!isset($formattedTimespans[$dayOfWeek])) {
                    $formattedTimespans[$dayOfWeek] =
                        "<meta itemprop=\"openingHours\" datetime=\"$daySpanShort $firstOpens-$lastCloses\"> "
                        . '</meta> '
                        . '<li itemprop="openingHoursSpecification"> '
                        . "<span class=\"cf-days\">$daySpanLong</span> "
                        . "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">"
                        . $this->translator->translate('from_hour') . '</span> '
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">"
                        . $this->translator->translate('till_hour') . '</span> '
                        . "<span class=\"cf-time\">$closes</span>"
                        . $childcare;
                } else {
                    $formattedTimespans[$dayOfWeek] .=
                        "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">"
                        . $this->translator->translate('and') . ' '
                        . $this->translator->translate('from_hour') . '</span> '
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">"
                        . $this->translator->translate('till_hour') . '</span> '
                        . "<span class=\"cf-time\">$closes</span>"
                        . $childcare;
                }
            }
        }

        foreach ($childcarePerDay as $dayOfWeek => $childcareOfDay) {
            $formattedTimespans[$dayOfWeek] .= $this->generateChildcare($childcareOfDay);
        }

        // Render the rest of the week scheme output.
        foreach ($formattedTimespans as $formattedTimespan) {
            $outputWeek .= $formattedTimespan . '</li>';
        }

        if ($sharedChildcare !== null) {
            $outputWeek .= '<li class="cf-childcare">'
                . ChildcareFormatter::forChildcare($sharedChildcare, $this->translator)
                    ->forEveryDay()
                    ->capitalize()
                    ->toString()
                . '</li>';
        }

        return $outputWeek . '</ul>';
    }

    private function generateChildcare(?Childcare $childcare): string
    {
        if ($childcare === null) {
            return '';
        }

        return ' <span class="cf-childcare">'
            . ChildcareFormatter::forChildcare($childcare, $this->translator)
                ->withBraces()
                ->capitalize()
                ->toString()
            . '</span>';
    }
}
