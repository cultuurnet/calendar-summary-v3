<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTime;

/**
 * Provide a large HTML formatter for periodic calendar type.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class LargePeriodicHTMLFormatter extends LargePeriodicFormatter implements PeriodicFormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function format(Offer $offer)
    {
        $output = $this->generateDates(
            $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get())),
            $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()))
        );

        if ($offer->getOpeningHours()) {
            $output .= $this->generateWeekScheme($offer->getOpeningHours());
        }

        return $this->formatSummary($output);
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
     * @param DateTime $dateFrom
     * @param DateTime $dateTo
     * @return string
     */
    protected function generateDates(DateTime $dateFrom, DateTime $dateTo)
    {

        $intlDateFrom =$this->fmt->format($dateFrom);
        $intlDateTo = $this->fmt->format($dateTo);

        $outputDates = '<p class="cf-period">';
        $outputDates .= '<time itemprop="startDate" datetime="' . $dateFrom->format("Y-m-d") . '">';
        $outputDates .= '<span class="cf-date">' . $intlDateFrom . '</span> </time>';
        $outputDates .= '<span class="cf-to cf-meta">' . $this->trans->getTranslations()->t('till') . '</span>';
        $outputDates .= '<time itemprop="endDate" datetime="' . $dateTo->format("Y-m-d") . '">';
        $outputDates .= '<span class="cf-date">' . $intlDateTo . '</span> </time>';
        $outputDates .= '</p>';
        return $outputDates;
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

    /**
     * @param $openingHoursData
     * @return string
     */
    protected function generateWeekScheme($openingHoursData)
    {
        $outputWeek = '<p class="cf-openinghours">' . $this->trans->getTranslations()->t('open') . ':</p>';
        $outputWeek .= '<ul class="list-unstyled">';

        // Create an array with formatted timespans.
        $formattedTimespans = [];

        foreach ($openingHoursData as $openingHours) {
            $daysOfWeek = $openingHours->getDaysOfWeek();
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

        // Render the rest of the week scheme output.
        foreach ($formattedTimespans as $formattedTimespan) {
            $outputWeek .= $formattedTimespan . '</li>';
        }
        return $outputWeek . '</ul>';
    }
}
