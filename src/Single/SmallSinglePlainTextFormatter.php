<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Event;
use IntlDateFormatter;

class SmallSinglePlainTextFormatter implements SingleFormatterInterface
{
    private $fmtDay;

    private $fmtMonth;

    public function __construct()
    {
        $this->fmtDay = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd'
        );

        $this->fmtMonth = new IntlDateFormatter(
            'nl_BE',
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'MMM'
        );
    }
    /**
    * Return xs formatted single date string.
    *
    * @param \CultuurNet\SearchV3\ValueObjects\Event $event
    * @return string
    */
    public function format(Event $event)
    {
        $dateFrom = $event->getStartDate();

        $dateFromDay = $this->fmtDay->format($dateFrom);
        $dateFromMonth = rtrim($this->fmtMonth->format($dateFrom), '.');

        $output = $dateFromDay . ' ' . $dateFromMonth;

        return $output;
    }
}
