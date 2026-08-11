<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\ChildcareFormatter;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAdjustedDaysFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\HtmlClosedDaysFormatter;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHours;
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
        if (!$offer->isAvailable()) {
            return HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
                ->withElement('p')
                ->withoutBraces()
                ->capitalize()
                ->toString();
        }

        if (!$offer->getOpeningHours()->isEmpty()) {
            $output = $this->generateWeekScheme($offer->getOpeningHours());
        } else {
            $output = '<p class="cf-openinghours">'
                . ucfirst($this->translator->translate('open_every_day'))
                . '</p>';
        }

        $output .= $this->adjustedDaysFormatter->format($offer->getAdjustedDays());
        $output .= $this->closedDaysFormatter->format($offer->getClosedDays());

        return $this->formatSummary($output);
    }

    private function formatSummary(string $calsum): string
    {
        $calsum = str_replace('><', '> <', $calsum);
        return str_replace('  ', ' ', $calsum);
    }

    private function generateWeekScheme(OpeningHours $openingHours): string
    {
        $outputWeek = '<ul class="list-unstyled">';
        // Create an array with formatted timespans.
        $formattedTimespans = [];

        // When every day has the same childcare it is summarized in a single list item
        // instead of being repeated on every day.
        $sharedChildcare = $openingHours->sharedChildcare();

        foreach ($openingHours as $openingHour) {
            $daysOfWeek = $openingHour->getDaysOfWeek();
            $firstOpens = OpeningHourFormatter::format($openingHours->earliestTimeOn($daysOfWeek));
            $lastCloses = OpeningHourFormatter::format($openingHours->latestTimeOn($daysOfWeek));
            $opens = OpeningHourFormatter::format($openingHour->getOpens());
            $closes = OpeningHourFormatter::format($openingHour->getCloses());
            $childcare = $sharedChildcare === null ? $this->generateChildcare($openingHour) : '';

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
                        . "<span class=\"cf-time\">$closes</span>"
                        . $childcare;
                } else {
                    $and = strpos($formattedTimespans[$dayOfWeek], 'cf-to') ? $this->translator->translate('and') . ' ' : '';
                    $formattedTimespans[$dayOfWeek] .=
                        "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">"
                        . $and . $this->translator->translate('from_hour') . '</span> '
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">"
                        . $this->translator->translate('till_hour') . '</span> '
                        . "<span class=\"cf-time\">$closes</span>"
                        . $childcare;
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

    private function generateChildcare(OpeningHour $openingHour): string
    {
        $childcare = $openingHour->getChildcare();

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
