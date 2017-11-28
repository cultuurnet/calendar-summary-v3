<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Event;
use IntlDateFormatter;

class MediumSingleHTMLFormatter implements SingleFormatterInterface
{
    private $fmt;

    private $fmtDay;

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

        $this->fmtDay = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'eeee'
        );
    }

    /**
    * Return medium formatted single date string.
    *
    * @param \CultuurNet\SearchV3\ValueObjects\Event $event
    * @return string
    */
    public function format(Event $event)
    {
        $date = $event->getStartDate();
        $intlDate = $this->fmt->format($date);
        $intlDateDay = $this->fmtDay->format($date);


        $output = '<span class="cf-weekday cf-meta">' . $intlDateDay . '</span>';
        $output .= ' ';
        $output .= '<span class="cf-date">' . $intlDate . '</span>';

        return $output;
    }
}
