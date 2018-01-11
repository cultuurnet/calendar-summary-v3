<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Event;

class SmallSinglePlainTextFormatter extends SmallSingleFormatter implements SingleFormatterInterface
{
    /**
    * Return xs or sm formatted single date string.
    *
    * @param \CultuurNet\SearchV3\ValueObjects\Event $event
    * @return string
    */
    public function format(Event $event)
    {
        $dateFrom = $event->getStartDate();
        $dateEnd = $event->getEndDate();

        if ($dateFrom->format('Y-m-d') == $dateEnd->format('Y-m-d')) {
            $output = $this->formatSameDay($dateFrom);
        } else {
            $output = $this->formatMoreDays($dateFrom, $dateEnd);
        }

        return $output;
    }

    protected function formatSameDay($dateFrom)
    {
        $dateFromDay = $this->fmtDay->format($dateFrom);
        $dateFromMonth = rtrim($this->fmtMonth->format($dateFrom), '.');

        $output = $dateFromDay . ' ' . $dateFromMonth;

        return $output;
    }

    protected function formatMoreDays($dateFrom, $dateEnd)
    {
        $dateFromDay = $this->fmtDay->format($dateFrom);
        $dateFromMonth = rtrim($this->fmtMonth->format($dateFrom), '.');

        $dateEndDay = $this->fmtDay->format($dateEnd);
        $dateEndMonth = rtrim($this->fmtMonth->format($dateEnd), '.');

        $output = 'Van ' . $dateFromDay . ' ' . $dateFromMonth . ' tot ' . $dateEndDay . ' ' . $dateEndMonth;

        return $output;
    }
}
