<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\HtmlOpeningHoursExceptionsFormatter;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\OpeningHourFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeImmutable;

final class ExtraLargePermanentHTMLFormatter implements PermanentFormatterInterface
{
    private $daysOfWeek = [
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];

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
        if (!$offer->isAvailable()) {
            return HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
                ->withElement('p')
                ->withoutBraces()
                ->capitalize()
                ->toString();
        }

        if ($offer->getOpeningHours()) {
            $output = $this->generateWeekScheme($offer->getOpeningHours());
        } else {
            $output = '<p class="cf-openinghours">'
                . ucfirst($this->translator->translate('open_every_day'))
                . '</p>';
        }

        $output .= $this->exceptionsFormatter->formatAdjustedDays($offer->getOpeningHoursAdjustedDays());
        $output .= $this->exceptionsFormatter->formatClosedDays($offer->getOpeningHoursClosedDays());

        return $this->formatSummary($output);
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
     * @param OpeningHour[] $openingHoursData
     */
    private function generateWeekScheme(array $openingHoursData): string
    {
        $outputWeek = '<ul class="list-unstyled">';
        // Create an array with formatted timespans.
        $formattedTimespans = [];

        foreach ($openingHoursData as $openingHours) {
            $daysOfWeek = $openingHours->getDaysOfWeek();
            $firstOpens = OpeningHourFormatter::format($this->getEarliestTime($openingHoursData, $daysOfWeek));
            $lastCloses = OpeningHourFormatter::format($this->getLatestTime($openingHoursData, $daysOfWeek));
            $opens = OpeningHourFormatter::format($openingHours->getOpens());
            $closes = OpeningHourFormatter::format($openingHours->getCloses());

            foreach ($daysOfWeek as $dayOfWeek) {
                $daySpanShort = ucfirst($this->formatter->formatAsAbbreviatedDayOfWeek(
                    new DateTimeImmutable($dayOfWeek)
                ));
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
                        . "<span class=\"cf-time\">$closes</span>";
                } else {
                    $and = strpos($formattedTimespans[$dayOfWeek], 'cf-to') ? $this->translator->translate('and') . ' ' : '';
                    $formattedTimespans[$dayOfWeek] .=
                        "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">"
                        . $and . $this->translator->translate('from_hour') . '</span> '
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">"
                        . $this->translator->translate('till_hour') . '</span> '
                        . "<span class=\"cf-time\">$closes</span>";
                }
            }
        }

        // Create an array with formatted closed days.
        $closedDays = [];
        foreach ($this->daysOfWeek as $day) {
            $closedDays[$day] = ucfirst($this->formatter->formatAsDayOfWeek(new DateTimeImmutable($day)));
        }

        $sortedTimespans = [];
        foreach ($this->daysOfWeek as $day) {
            $translatedDay = ucfirst($this->formatter->formatAsDayOfWeek(new DateTimeImmutable($day)));

            if (isset($formattedTimespans[$day])) {
                $sortedTimespans[$day] = $formattedTimespans[$day];
            } else {
                $sortedTimespans[$day] =
                    "<meta itemprop=\"openingHours\" datetime=\"$translatedDay\"> "
                    . '</meta> '
                    . '<li itemprop="openingHoursSpecification"> '
                    . "<span class=\"cf-days\">$closedDays[$day]</span> "
                    . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">'
                    . $this->translator->translate('closed') . '</span> ';
            }
        }

        // Render the rest of the week scheme output.
        foreach ($sortedTimespans as $sortedTimespan) {
            $outputWeek .= $sortedTimespan . '</li>';
        }
        return $outputWeek . '</ul>';
    }
}
