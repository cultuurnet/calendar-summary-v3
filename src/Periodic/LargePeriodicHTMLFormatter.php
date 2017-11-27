<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use DateTime;
use IntlDateFormatter;

class LargePeriodicHTMLFormatter implements PeriodicFormatterInterface
{

    /**
     * Translate the day to Dutch.
     */
    protected $mapping_days = array(
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
    protected $mapping_short_days = array(
        'monday' => 'Mo',
        'tuesday' => 'Tu',
        'wednesday' => 'We',
        'thursday' => 'Th',
        'friday' => 'Fr',
        'saturday' => 'Sa',
        'sunday' => 'Su',
    );

    public function format($place) {
        $output = $this->generateDates($place->getStartDate(), $place->getEndDate());

        if ($place->getOpeningHours()) {
            $output .= $this->generateWeekScheme($place->getOpeningHours());
        }

        return $this->formatSummary($output);
    }

    protected function formatSummary($calsum)
    {
        $calsum = str_replace('><', '> <', $calsum);
        return str_replace('  ', ' ', $calsum);
    }

    protected function getFormattedTime($time)
    {
        $formatted_short_time = ltrim($time, '0');
        if ($formatted_short_time == ':00') {
            $formatted_short_time = '0:00';
        }
        return $formatted_short_time;
    }

    protected function getEarliestTime($openingHoursData, $daysOfWeek)
    {
        $earliest = '';
        foreach ($openingHoursData as $openingHours) {
            if ($daysOfWeek === $openingHours->getDayOfWeek()) {
                if (!empty($earliest)) {
                    if ($earliest > $openingHours->getOpens()) {
                        $earliest = $openingHours->getOpens();
                    }
                }
                else {
                    $earliest = $openingHours->getOpens();
                }
            };
        }
        return $earliest;
    }

    protected function getLatestTime($openingHoursData, $daysOfWeek)
    {
        $latest = '';
        foreach ($openingHoursData as $openingHours) {
            if ($daysOfWeek === $openingHours->getDayOfWeek()) {
                if (!empty($latest)) {
                    if ($openingHours->getCloses() > $latest) {
                        $latest = $openingHours->getCloses();
                    }
                }
                else {
                    $latest = $openingHours->getCloses();
                }
            };
        }
        return $latest;
    }


    protected function generateDates(DateTime $dateFrom, DateTime $dateTo)
    {
        $fmt = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );

        $dateFromTimestamp = $dateFrom->getTimestamp();
        $intlDateFrom =$fmt->format($dateFromTimestamp);

        $dateToTimestamp = $dateTo->getTimestamp();
        $intlDateTo = $fmt->format($dateToTimestamp);

        $output_dates = '<p class="cf-period">';
        $output_dates .= '<time itemprop="startDate" datetime="' . date("Y-m-d", $dateFromTimestamp) . '">';
        $output_dates .= '<span class="cf-date">' . $intlDateFrom . '</span> </time>';
        $output_dates .= '<span class="cf-to cf-meta">tot</span>';
        $output_dates .= '<time itemprop="endDate" datetime="' . date("Y-m-d", $dateToTimestamp) . '">';
        $output_dates .= '<span class="cf-date">' . $intlDateTo . '</span> </time>';
        $output_dates .= '</p>';
        return $output_dates;
    }

    protected function generateFormattedTimespan($daysOfWeek, $long = false) {
        if ($long) {
            return (count($daysOfWeek) > 1 ? ucfirst($this->mapping_days[$daysOfWeek[0]]) . ' - ' . $this->mapping_days[$daysOfWeek[count($daysOfWeek)-1]] : ucfirst($this->mapping_days[$daysOfWeek[0]]));
        }
        else {
            return (count($daysOfWeek) > 1 ? $this->mapping_short_days[$daysOfWeek[0]] . '-' . $this->mapping_short_days[$daysOfWeek[count($daysOfWeek)-1]] : $this->mapping_short_days[$daysOfWeek[0]]);
        }
    }

    protected function generateWeekscheme($openingHoursData)
    {
        $output_week = '<p class="cf-openinghours">Open op:</p>';
        $output_week .= '<ul class="list-unstyled">';

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
                $formattedTimespans[$daySpanShort] = <<<EOT
<meta itemprop="openingHours" datetime="$daySpanShort $firstOpens-$lastCloses"> </meta> <li itemprop="openingHoursSpecification"> <span class="cf-days">$daySpanLong</span> <span itemprop="opens" content="$opens" class="cf-from cf-meta">van</span> <span class="cf-time">$opens</span> <span itemprop="closes" content="$closes" class="cf-to cf-meta">tot</span> <span class="cf-time">$closes</span> 
EOT;
            }
            else {
                $formattedTimespans[$daySpanShort] .= <<<EOT
<span itemprop="opens" content="$opens" class="cf-from cf-meta">van</span> <span class="cf-time">$opens</span> <span itemprop="closes" content="$closes" class="cf-to cf-meta">tot</span> <span class="cf-time">$closes</span> 
EOT;
            }
        }

        // Render the rest of the week scheme output.
        foreach ($formattedTimespans as $formattedTimespan) {
            $output_week .= $formattedTimespan . '</li>';
        }
        return $output_week . '</ul>';
    }
}
