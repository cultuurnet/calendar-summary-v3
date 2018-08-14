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
    protected function generateFormattedTimespan($daysOfWeek, $long = false)
    {
        if ($long) {
            if (count($daysOfWeek) > 1) {
                return ucfirst($daysOfWeek[0]) . ' - ' . $daysOfWeek[count($daysOfWeek)-1];
            } else {
                return ucfirst($daysOfWeek[0]);
            }
        } else {
            if (count($daysOfWeek) > 1) {
                return $daysOfWeek[0] . '-' . $daysOfWeek[count($daysOfWeek)-1];
            } else {
                return $daysOfWeek[0];
            }
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
            $daysOfWeekTranslated = $openingHours->getDaysOfWeek();
            $daysOfWeekShortTranslated = $openingHours->getDaysOfWeek();
            foreach($daysOfWeek as $i => $dayOfWeek) {
                $daysOfWeekTranslated[$i] = $this->fmtDays->format(strtotime($dayOfWeek));
                $daysOfWeekShortTranslated[$i] = $this->fmtShortDays->format(strtotime($dayOfWeek));
            }

            $daySpanShort = $this->generateFormattedTimespan($daysOfWeekShortTranslated);
            $daySpanLong = $this->generateFormattedTimespan($daysOfWeekTranslated, true);
            $firstOpens = $this->getFormattedTime($this->getEarliestTime($openingHoursData, $daysOfWeek));
            $lastCloses = $this->getFormattedTime($this->getLatestTime($openingHoursData, $daysOfWeek));
            $opens = $this->getFormattedTime($openingHours->getOpens());
            $closes = $this->getFormattedTime($openingHours->getCloses());
            // Determine whether to add a new timespan with included meta tag,
            // or to add extra opening times to an existing timespan.
            if (!isset($formattedTimespans[$daySpanShort])) {
                $formattedTimespans[$daySpanShort] =
                    "<meta itemprop=\"openingHours\" datetime=\"$daySpanShort $firstOpens-$lastCloses\"> "
                    . "</meta> "
                    . "<li itemprop=\"openingHoursSpecification\"> "
                    . "<span class=\"cf-days\">$daySpanLong</span> "
                    . "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">" . $this->trans->getTranslations()->t('from') . "</span> $opens "
                    . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">" . $this->trans->getTranslations()->t('till') . "</span> $closes";
            } else {
                $formattedTimespans[$daySpanShort] .=
                    "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">" . $this->trans->getTranslations()->t('from') . "</span> $opens "
                    . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">" . $this->trans->getTranslations()->t('till') . "</span> $closes";
            }
        }
        // Render the rest of the week scheme output.
        foreach ($formattedTimespans as $formattedTimespan) {
            $outputWeek .= $formattedTimespan . '</li>';
        }
        return $outputWeek . '</ul>';
    }
}
