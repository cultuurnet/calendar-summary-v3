<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\Place;

class LargePermanentHTMLFormatter implements PermanentFormatterInterface
{
    /**
     * Translate the day to Dutch.
     */
    protected $mappingDays = array(
        'monday' => 'maandag',
        'tuesday' => 'dinsdag',
        'wednesday' => 'woensdag',
        'thursday' => 'donderdag',
        'friday' => 'vrijdag',
        'saturday' => 'zaterdag',
        'sunday' => 'zondag',
    );

    /**
     * Translate the day to short Dutch format.
     */
    protected $mappingShortDays = array(
        'monday' => 'Mo',
        'tuesday' => 'Tu',
        'wednesday' => 'We',
        'thursday' => 'Th',
        'friday' => 'Fr',
        'saturday' => 'Sa',
        'sunday' => 'Su',
    );

    public function format(Place $place) {
        $output = '';
        if ($place->getOpeningHours()) {
            $output .= $this->generateWeekScheme($place->getOpeningHours());
        }

        return $this->formatSummary($output);
    }

    protected function getFormattedTime($time)
    {
        $formattedShortTime = ltrim($time, '0');
        if ($formattedShortTime == ':00') {
            $formattedShortTime = '0:00';
        }
        return $formattedShortTime;
    }

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
            if ($daysOfWeek === $openingHours->getDayOfWeek()) {
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
            if ($daysOfWeek === $openingHours->getDayOfWeek()) {
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
     * @param $daysOfWeek
     * @param bool $long
     * @return string
     */
    protected function generateFormattedTimespan($daysOfWeek, $long = false)
    {
        if ($long) {
            if (count($daysOfWeek) > 1) {
                return ucfirst($this->mappingDays[$daysOfWeek[0]])
                    . ' - '
                    . $this->mappingDays[$daysOfWeek[count($daysOfWeek)-1]];
            } else {
                return ucfirst($this->mappingDays[$daysOfWeek[0]]);
            }
        } else {
            if (count($daysOfWeek) > 1) {
                return ucfirst($this->mappingShortDays[$daysOfWeek[0]])
                    . '-'
                    . $this->mappingShortDays[$daysOfWeek[count($daysOfWeek)-1]];
            } else {
                return ucfirst($this->mappingShortDays[$daysOfWeek[0]]);
            }
        }
    }

    protected function generateWeekScheme($openingHoursData)
    {
        $outputWeek = '<ul class="list-unstyled">';
        // Create an array with formatted timespans.
        $formattedTimespans = [];
        foreach ($openingHoursData as $openingHours) {
            $daysOfWeek = $openingHours->getDayOfWeek();
            $daySpanShort = $this->generateFormattedTimespan($daysOfWeek);
            $daySpanLong = $this->generateFormattedTimespan($daysOfWeek, true);
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
                    . "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">van</span>$opens"
                    . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">tot</span>$closes";
            } else {
                $formattedTimespans[$daySpanShort] .=
                    "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">van</span>$opens"
                    . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">tot</span>$closes";
            }
        }
        // Render the rest of the week scheme output.
        foreach ($formattedTimespans as $formattedTimespan) {
            $outputWeek .= $formattedTimespan . '</li>';
        }
        return $outputWeek . '</ul>';
    }
}
