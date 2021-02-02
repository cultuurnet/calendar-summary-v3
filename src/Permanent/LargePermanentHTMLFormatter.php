<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\OpeningHourFormatter;
use CultuurNet\CalendarSummaryV3\TranslatedStatusReasonFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use DateTimeImmutable;

final class LargePermanentHTMLFormatter implements PermanentFormatterInterface
{
    private $daysOfWeek = array(
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday'
    );

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
        if ($offer->getStatus()->getType() !== 'Available') {
            $statusText = $offer->getStatus()->getType() === 'Unavailable' ?
                $this->translator->translate('cancelled') :
                $this->translator->translate('postponed');

            $reasonFormatter = new TranslatedStatusReasonFormatter($this->translator);
            $titleAttribute = $reasonFormatter->formatAsTitleAttribute($offer->getStatus());

            return '<p ' . $titleAttribute . 'class="cf-openinghours">' . ucfirst($statusText) . '</p>';
        }

        if ($offer->getOpeningHours()) {
            return $this->formatSummary($this->generateWeekScheme($offer->getOpeningHours()));
        }

        return $this->formatSummary(
            '<p class="cf-openinghours">'
            . ucfirst($this->translator->translate('always_open'))
            . '</p>'
        );
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
     * @return string
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
     * @return string
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

    private function generateFormattedTimespan(string $dayOfWeek, bool $long = false): string
    {
        if ($long) {
            return ucfirst($this->translator->translate($dayOfWeek));
        } else {
            //return ucfirst($this->mappingShortDays[$dayOfWeek]);
            return ucfirst($this->translator->translate($dayOfWeek . 'Short'));
        }
    }

    /**
     * @param OpeningHours[] $openingHoursData
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
                        . "</meta> "
                        . "<li itemprop=\"openingHoursSpecification\"> "
                        . "<span class=\"cf-days\">$daySpanLong</span> "
                        . "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">"
                        . $this->translator->translate('from') . "</span> "
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">"
                        . $this->translator->translate('till') . "</span> "
                        . "<span class=\"cf-time\">$closes</span>";
                } else {
                    $formattedTimespans[$dayOfWeek] .=
                        "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">"
                        . $this->translator->translate('from') . "</span> "
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">"
                        . $this->translator->translate('till') . "</span> "
                        . "<span class=\"cf-time\">$closes</span>";
                }
            }
        }

        // Create an array with formatted closed days.
        $closedDays = [];
        foreach ($this->daysOfWeek as $day) {
            $closedDays[$day] = ucfirst($this->formatter->formatAsDayOfWeek(new DateTimeImmutable($day)));
        }

        $sortedTimespans = array();
        foreach ($this->daysOfWeek as $day) {
            $translatedDay = ucfirst($this->formatter->formatAsDayOfWeek(new DateTimeImmutable($day)));

            if (isset($formattedTimespans[$day])) {
                $sortedTimespans[$day] = $formattedTimespans[$day];
            } else {
                $sortedTimespans[$day] =
                    "<meta itemprop=\"openingHours\" datetime=\"$translatedDay\"> "
                    . "</meta> "
                    . "<li itemprop=\"openingHoursSpecification\"> "
                    . "<span class=\"cf-days\">$closedDays[$day]</span> "
                    . "<span itemprop=\"closed\" content=\"closed\" class=\"cf-closed cf-meta\">"
                    . $this->translator->translate('closed') . "</span> ";
            }
        }

        // Render the rest of the week scheme output.
        foreach ($sortedTimespans as $sortedTimespan) {
            $outputWeek .= $sortedTimespan . '</li>';
        }
        return $outputWeek . '</ul>';
    }
}
