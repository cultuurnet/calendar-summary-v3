<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\ChildcareFormatter;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\HtmlOpeningHoursExceptionsFormatter;
use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
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
     * @var HtmlOpeningHoursExceptionsFormatter
     */
    private $exceptionsFormatter;

    public function __construct(Translator $translator)
    {
        $this->formatter = new DateFormatter($translator->getLocale());
        $this->translator = $translator;
        $this->exceptionsFormatter = new HtmlOpeningHoursExceptionsFormatter($translator);
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

        if ($offer->getOpeningHours()) {
            $output .= $this->generateWeekScheme($offer->getOpeningHours());
        }

        $output .= $this->exceptionsFormatter->formatAdjustedDays($offer->getOpeningHoursAdjustedDays());
        $output .= $this->exceptionsFormatter->formatClosedDays($offer->getOpeningHoursClosedDays());

        return trim($this->formatSummary($output));
    }

    private function formatSummary(string $calsum): string
    {
        $calsum = str_replace('><', '> <', $calsum);
        return str_replace('  ', ' ', $calsum);
    }

    /**
     * Retrieve the earliest time for the specified daysOfWeek.
     *
     * @param OpeningHour[] $openingHoursData
     * @param string[] $daysOfWeek
     */
    private function getEarliestTime(array $openingHoursData, array $daysOfWeek): string
    {
        $earliest = '';
        foreach ($openingHoursData as $openingHours) {
            if ($daysOfWeek === $openingHours->getDaysOfWeek()) {
                if (!empty($earliest)) {
                    $earliest = ($openingHours->getOpens() < $earliest ? $openingHours->getOpens() : $earliest);
                } else {
                    $earliest = $openingHours->getOpens();
                }
            };
        }
        return $earliest;
    }

    /**
     * Retrieve the latest time for the specified daysOfWeek.
     *
     * @param OpeningHour[] $openingHoursData
     * @param string[] $daysOfWeek
     */
    private function getLatestTime(array $openingHoursData, array $daysOfWeek): string
    {
        $latest = '';
        foreach ($openingHoursData as $openingHours) {
            if ($daysOfWeek === $openingHours->getDaysOfWeek()) {
                if (!empty($latest)) {
                    $latest = ($openingHours->getCloses() > $latest ? $openingHours->getCloses() : $latest);
                } else {
                    $latest = $openingHours->getCloses();
                }
            };
        }
        return $latest;
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
     * @param OpeningHour[] $openingHoursData
     * @return string
     */
    private function generateWeekScheme($openingHoursData)
    {
        $outputWeek = '<p class="cf-openinghours">' . ucfirst($this->translator->translate('open_at')) . ':</p>';
        $outputWeek .= '<ul class="list-unstyled">';

        // Create an array with formatted timespans.
        $formattedTimespans = [];

        // When every day has the same childcare it is summarized in a single list item
        // instead of being repeated on every day.
        $sharedChildcare = Childcare::sharedByAll($openingHoursData);

        foreach ($openingHoursData as $openingHours) {
            $daysOfWeek = $openingHours->getDaysOfWeek();
            $firstOpens = OpeningHourFormatter::format($this->getEarliestTime($openingHoursData, $daysOfWeek));
            $lastCloses = OpeningHourFormatter::format($this->getLatestTime($openingHoursData, $daysOfWeek));
            $opens = OpeningHourFormatter::format($openingHours->getOpens());
            $closes = OpeningHourFormatter::format($openingHours->getCloses());
            $childcare = $sharedChildcare === null ? $this->generateChildcare($openingHours) : '';

            foreach ($daysOfWeek as $dayOfWeek) {
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

    private function generateChildcare(OpeningHour $openingHours): string
    {
        $childcare = $openingHours->getChildcare();

        if ($childcare === null) {
            return '';
        }

        return ' <span class="cf-childcare">'
            . ChildcareFormatter::forChildcare($childcare, $this->translator)
                ->withBraces()
                ->toString()
            . '</span>';
    }
}
