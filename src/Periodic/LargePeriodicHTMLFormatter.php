<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlStatusFormatter;
use CultuurNet\CalendarSummaryV3\OpeningHourFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use DateTime;
use DateTimeImmutable;

final class LargePeriodicHTMLFormatter implements PeriodicFormatterInterface
{
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
        $output = $this->generateDates(
            $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get())),
            $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()))
        );

        if ($offer->getOpeningHours()) {
            $output .= $this->generateWeekScheme($offer->getOpeningHours());
        }

        $optionalStatus = HtmlStatusFormatter::forOffer($offer, $this->translator)
            ->withBraces()
            ->toString();

        return trim($this->formatSummary($output) . ' ' . $optionalStatus);
    }

    private function formatSummary(string $calsum): string
    {
        $calsum = str_replace('><', '> <', $calsum);
        return str_replace('  ', ' ', $calsum);
    }

    /**
     * Retrieve the earliest time for the specified daysOfWeek.
     *
     * @param OpeningHours[] $openingHoursData
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
     * @param OpeningHours[] $openingHoursData
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
    private function generateDates(DateTime $dateFrom, DateTime $dateTo)
    {
        $intlDateFrom =$this->formatter->formatAsFullDate($dateFrom);
        $intlDateTo = $this->formatter->formatAsFullDate($dateTo);

        return '<p class="cf-period">'
            . '<time itemprop="startDate" datetime="' . $dateFrom->format('Y-m-d') . '">'
            . '<span class="cf-date">' . $intlDateFrom . '</span> </time>'
            . '<span class="cf-to cf-meta">' . $this->translator->translate('till') . '</span>'
            . '<time itemprop="endDate" datetime="' . $dateTo->format('Y-m-d') . '">'
            . '<span class="cf-date">' . $intlDateTo . '</span> </time>'
            . '</p>';
    }

    /**
     * @param OpeningHours[] $openingHoursData
     * @return string
     */
    private function generateWeekScheme($openingHoursData)
    {
        $outputWeek = '<p class="cf-openinghours">' . ucfirst($this->translator->translate('open')) . ':</p>';
        $outputWeek .= '<ul class="list-unstyled">';

        // Create an array with formatted timespans.
        $formattedTimespans = [];

        foreach ($openingHoursData as $openingHours) {
            $daysOfWeek = $openingHours->getDaysOfWeek();
            $firstOpens = OpeningHourFormatter::format($this->getEarliestTime($openingHoursData, $daysOfWeek));
            $lastCloses = OpeningHourFormatter::format($this->getLatestTime($openingHoursData, $daysOfWeek));
            $opens = OpeningHourFormatter::format($openingHours->getOpens());
            $closes = OpeningHourFormatter::format($openingHours->getCloses());

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
                        . $this->translator->translate('from') . '</span> '
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">"
                        . $this->translator->translate('till') . '</span> '
                        . "<span class=\"cf-time\">$closes</span>";
                } else {
                    $formattedTimespans[$dayOfWeek] .=
                        "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">"
                        . $this->translator->translate('and') . ' '
                        . $this->translator->translate('from') . '</span> '
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">"
                        . $this->translator->translate('till') . '</span> '
                        . "<span class=\"cf-time\">$closes</span>";
                }
            }
        }

        // Render the rest of the week scheme output.
        foreach ($formattedTimespans as $formattedTimespan) {
            $outputWeek .= $formattedTimespan . '</li>';
        }
        return $outputWeek . '</ul>';
    }
}
