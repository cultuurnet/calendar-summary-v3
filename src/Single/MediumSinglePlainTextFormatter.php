<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Event;

class MediumSinglePlainTextFormatter extends MediumSingleFormatter implements SingleFormatterInterface
{

    /**
    * Return medium formatted single date string.
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
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlDateDayFrom = $this->fmtDay->format($dateFrom);

        $output = $intlDateDayFrom . ' ' . $intlDateFrom;

        return $output;
    }

    protected function formatMoreDays($dateFrom, $dateEnd)
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlDateDayFrom = $this->fmtDay->format($dateFrom);

        $intlDateEnd = $this->fmt->format($dateEnd);
        $intlDateDayEnd = $this->fmtDay->format($dateEnd);

        $output = 'Van ' . $intlDateDayFrom . ' ' . $intlDateFrom . ' tot ' . $intlDateDayEnd . ' ' . $intlDateEnd;

        return $output;
    }
}
