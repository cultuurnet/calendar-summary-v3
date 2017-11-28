<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Event;
use IntlDateFormatter;

class LargeSinglePlainTextFormatter implements SingleFormatterInterface
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

        $output = $intlWeekDay . ' ' . $intlDate . PHP_EOL;
        $output .= 'van ';
        $output .= $intlStartTime;
        $output .= ' tot ' . $intlEndTime;

        return $output;
    }
}
