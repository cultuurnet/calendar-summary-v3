<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;

class LargePermanentHTMLFormatter extends LargePermanentFormatter implements PermanentFormatterInterface
{
    public function format(Offer $offer): string
    {
        $output = '';
        if ($offer->getOpeningHours()) {
            $output .= $this->generateWeekScheme($offer->getOpeningHours());
        } else {
            $output .= '<p class="cf-openinghours">'
                . ucfirst($this->trans->getTranslations()->t('always_open')) . '</p>';
        }

        return $this->formatSummary($output);
    }

    protected function getFormattedTime(string $time): string
    {
        $formattedShortTime = ltrim($time, '0');
        if ($formattedShortTime == ':00') {
            $formattedShortTime = '0:00';
        }
        return $formattedShortTime;
    }

    protected function formatSummary(string $calsum): string
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
    protected function getEarliestTime(array $openingHoursData, array $daysOfWeek): string
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
    protected function getLatestTime(array $openingHoursData, array $daysOfWeek): string
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

    protected function generateFormattedTimespan(string $dayOfWeek, bool $long = false): string
    {
        if ($long) {
            return ucfirst($this->trans->getTranslations()->t($dayOfWeek));
        } else {
            //return ucfirst($this->mappingShortDays[$dayOfWeek]);
            return ucfirst($this->trans->getTranslations()->t($dayOfWeek . 'Short'));
        }
    }

    /**
     * @param OpeningHours[] $openingHoursData
     */
    protected function generateWeekScheme(array $openingHoursData): string
    {
        $outputWeek = '<ul class="list-unstyled">';
        // Create an array with formatted timespans.
        $formattedTimespans = [];

        foreach ($openingHoursData as $openingHours) {
            $daysOfWeek = $openingHours->getDaysOfWeek();
            $firstOpens = $this->getFormattedTime($this->getEarliestTime($openingHoursData, $daysOfWeek));
            $lastCloses = $this->getFormattedTime($this->getLatestTime($openingHoursData, $daysOfWeek));
            $opens = $this->getFormattedTime($openingHours->getOpens());
            $closes = $this->getFormattedTime($openingHours->getCloses());

            foreach ($daysOfWeek as $dayOfWeek) {
                $daySpanShort = ucfirst($this->fmtShortDays->format(strtotime($dayOfWeek)));
                $daySpanLong = ucfirst($this->fmtDays->format(strtotime($dayOfWeek)));

                if (!isset($formattedTimespans[$dayOfWeek])) {
                    $formattedTimespans[$dayOfWeek] =
                        "<meta itemprop=\"openingHours\" datetime=\"$daySpanShort $firstOpens-$lastCloses\"> "
                        . "</meta> "
                        . "<li itemprop=\"openingHoursSpecification\"> "
                        . "<span class=\"cf-days\">$daySpanLong</span> "
                        . "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">"
                        . $this->trans->getTranslations()->t('from') . "</span> "
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">"
                        . $this->trans->getTranslations()->t('till') . "</span> "
                        . "<span class=\"cf-time\">$closes</span>";
                } else {
                    $formattedTimespans[$dayOfWeek] .=
                        "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">"
                        . $this->trans->getTranslations()->t('from') . "</span> "
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">"
                        . $this->trans->getTranslations()->t('till') . "</span> "
                        . "<span class=\"cf-time\">$closes</span>";
                }
            }
        }

        // Create an array with formatted closed days.
        $closedDays = [];
        foreach ($this->daysOfWeek as $day) {
            $closedDays[$day] = ucfirst($this->fmtDays->format(strtotime($day)));
        }

        $sortedTimespans = array();
        foreach ($this->daysOfWeek as $day) {
            $translatedDay = ucfirst($this->fmtDays->format(strtotime($day)));

            if (isset($formattedTimespans[$day])) {
                $sortedTimespans[$day] = $formattedTimespans[$day];
            } else {
                $sortedTimespans[$day] =
                    "<meta itemprop=\"openingHours\" datetime=\"$translatedDay\"> "
                    . "</meta> "
                    . "<li itemprop=\"openingHoursSpecification\"> "
                    . "<span class=\"cf-days\">$closedDays[$day]</span> "
                    . "<span itemprop=\"closed\" content=\"closed\" class=\"cf-closed cf-meta\">"
                    . $this->trans->getTranslations()->t('closed') . "</span> ";
            }
        }

        // Render the rest of the week scheme output.
        foreach ($sortedTimespans as $sortedTimespan) {
            $outputWeek .= $sortedTimespan . '</li>';
        }
        return $outputWeek . '</ul>';
    }
}
