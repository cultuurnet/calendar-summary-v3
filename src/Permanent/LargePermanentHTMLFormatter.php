<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\Offer;

/**
 * Provide a large HTML formatter for permanent calendar type.
 * @package CultuurNet\CalendarSummaryV3\Permanent
 */
class LargePermanentHTMLFormatter extends LargePermanentFormatter implements PermanentFormatterInterface
{

    /**
     * {@inheritdoc}
     */
    public function format(Offer $offer)
    {
        $output = '';
        if ($offer->getOpeningHours()) {
            $output .= $this->generateWeekScheme($offer->getOpeningHours());
        } else {
            $output .= '<p class="cf-openinghours">' . ucfirst($this->trans->getTranslations()->t('always_open')) . '</p>';
        }

        return $this->formatSummary($output);
    }

    /**
     * @param $time
     * @return string
     */
    protected function getFormattedTime($time)
    {
        $formattedShortTime = ltrim($time, '0');
        if ($formattedShortTime == ':00') {
            $formattedShortTime = '0:00';
        }
        return $formattedShortTime;
    }

    /**
     * @param $calsum
     * @return mixed
     */
    protected function formatSummary($calsum)
    {
        $calsum = str_replace('><', '> <', $calsum);
        return str_replace('  ', ' ', $calsum);
    }

    /**
     * Retrieve the earliest time for the specified daysOfWeek.
     *
     * @param $openingHoursData
     * @param $daysOfWeek
     * @return string
     */
    protected function getEarliestTime($openingHoursData, $daysOfWeek)
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
     * @param $openingHoursData
     * @param $daysOfWeek
     * @return string
     */
    protected function getLatestTime($openingHoursData, $daysOfWeek)
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
     * Generate a formatted timespan.
     *
     * @param $daysOfWeek
     * @param bool $long
     * @return string
     */
    protected function generateFormattedTimespan($dayOfWeek, $long = false)
    {
        if ($long) {
            return ucfirst($this->mappingDays[$dayOfWeek]);
        } else {
            return ucfirst($this->mappingShortDays[$dayOfWeek]);
        }
    }

    /**
     * Generate a weekscheme based on the given opening hours.
     *
     * @param $openingHoursData
     * @return string
     */
    protected function generateWeekScheme($openingHoursData)
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
                $daySpanShort = $this->generateFormattedTimespan($dayOfWeek);
                $daySpanLong = $this->generateFormattedTimespan($dayOfWeek, true);

                if (!isset($formattedTimespans[$daySpanShort])) {
                    $formattedTimespans[$daySpanShort] =
                        "<meta itemprop=\"openingHours\" datetime=\"$daySpanShort $firstOpens-$lastCloses\"> "
                        . "</meta> "
                        . "<li itemprop=\"openingHoursSpecification\"> "
                        . "<span class=\"cf-days\">$daySpanLong</span> "
                        . "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">" . $this->trans->getTranslations()->t('from') . "</span> "
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">" . $this->trans->getTranslations()->t('till') . "</span> "
                        . "<span class=\"cf-time\">$closes</span>";
                } else {
                    $formattedTimespans[$daySpanShort] .=
                        "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">" . $this->trans->getTranslations()->t('from') . "</span> "
                        . "<span class=\"cf-time\">$opens</span> "
                        . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">" . $this->trans->getTranslations()->t('till') . "</span> "
                        . "<span class=\"cf-time\">$closes</span>";
                }
            }
        }

        // Create an array with formatted closed days.
        $closedDays = [];
        foreach ($this->mappingShortDays as $key => $day) {
            $closedDays[$day] = ucfirst($this->mappingDays[$key]);
        }

        $sortedTimespans = array();
        foreach ($this->mappingShortDays as $key => $day) {
            if (isset($formattedTimespans[$day])) {
                $sortedTimespans[$day] = $formattedTimespans[$day];
            } else {
                $sortedTimespans[$day] =
                    "<meta itemprop=\"openingHours\" datetime=\"$day\"> "
                    . "</meta> "
                    . "<li itemprop=\"openingHoursSpecification\"> "
                    . "<span class=\"cf-days\">$closedDays[$day]</span> "
                    . "<span itemprop=\"closed\" content=\"closed\" class=\"cf-closed cf-meta\">" . $this->trans->getTranslations()->t('closed') . "</span> ";
            }
        }

        // Render the rest of the week scheme output.
        foreach ($sortedTimespans as $sortedTimespan) {
            $outputWeek .= $sortedTimespan . '</li>';
        }
        return $outputWeek . '</ul>';

    }
}
