<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Event;
use IntlDateFormatter;

class LargeSingleHTMLFormatter implements SingleFormatterInterface
{
    private $fmt;

    private $fmtWeekDayLong;

    private $fmtTime;

    public function __construct()
    {
        $this->fmt = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );

        $this->fmtWeekDayLong = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'EEEE'
        );

        $this->fmtTime = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'HH:mm'
        );
    }

    /**
    * Return large formatted single date string.
    *
    * @param \CultuurNet\SearchV3\ValueObjects\Event $event
    * @return string
    */
    public function format(Event $event)
    {
        $startDate = $event->getStartDate();
        $intlDate = $this->fmt->format($startDate);
        $intlWeekDay = $this->fmtWeekDayLong->format($startDate);
        $intlStartTime = $this->fmtTime->format($startDate);

        $endDate = $event->getEndDate();
        $intlEndTime = $this->fmtTime->format($endDate);

        $output = '<time itemprop="startDate" datetime="' . $startDate->format(\DateTime::ATOM) . '">';
        $output .= '<span class="cf-weekday cf-meta">' . $intlWeekDay . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDate . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-from cf-meta">van</span>';
        $output .= ' ';
        $output .= '<span class="cf-time">' . $intlStartTime . '</span>';
        $output .= '</time>';
        $output .= ' ';
        $output .= '<span class="cf-to cf-meta">tot</span>';
        $output .= ' ';
        $output .= '<time itemprop="endDate" datetime="' . $endDate->format(\DateTime::ATOM) . '">';
        $output .= '<span class="cf-time">' . $intlEndTime . '</span>';
        $output .= '</time>';

        return $output;
    }
}
