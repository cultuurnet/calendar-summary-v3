<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\FormatterException;
use IntlDateFormatter;

class ExtraSmallSingleHTMLFormatter implements SingleFormatterInterface
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

    public function format(Event $event)
    {
        $subEvents = $event->getSubEvents();

        foreach ($subEvents as $key => $subEvent) {
            $dateFrom = new \DateTime($subEvent->startDate);

            $dateFromDay = $this->fmtDay->format($dateFrom);
            $dateFromMonth = $this->fmtMonth->format($dateFrom);

            $output = '<span class="cf-date">' . $dateFromDay . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-month">' . $dateFromMonth . '</span>';

            return $output;
        }

        // TODO: Clear out what to do with single types.
        /*$timestamps_count = iterator_count($timestampList);
        $timestampList->rewind();

        if ($timestamps_count == 1) {
            $timestamp = $timestampList->current();
            $dateFrom = $timestamp->getDate();

            $dateFromDay = $this->fmtDay->format(strtotime($dateFrom));
            $dateFromMonth = $this->fmtMonth->format(strtotime($dateFrom));
            $dateFromMonth = rtrim($dateFromMonth, ".");

            $output = '<span class="cf-date">' . $dateFromDay . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-month">' . $dateFromMonth . '</span>';

            return $output;
        } else {
            throw new FormatterException('xs format not supported for multiple timestamps.');
        }*/
    }
}
