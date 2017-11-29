<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Event;
use IntlDateFormatter;

class LargeSinglePlainTextFormatter extends LargeSingleFormatter implements SingleFormatterInterface
{

    /**
    * Return large formatted single date string.
    *
    * @param \CultuurNet\SearchV3\ValueObjects\Event $event
    * @return string
    */
    public function format(Event $event)
    {
        $dateFrom = $event->getStartDate();
        $dateEnd = $event->getEndDate();

        if ($dateFrom->format('Y-m-d') == $dateEnd->format('Y-m-d')) {
            $output = $this->formatSameDay($dateFrom, $dateEnd);
        } else {
            $output = $this->formatMoreDays($dateFrom, $dateEnd);
        }

        return $output;

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

    protected function formatSameDay($dateFrom, $dateEnd)
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlWeekDayFrom = $this->fmtWeekDayLong->format($dateFrom);
        $intlStartTimeFrom = $this->fmtTime->format($dateFrom);

        $intlEndTimeEnd = $this->fmtTime->format($dateEnd);

        $output = $intlWeekDayFrom . ' ' . $intlDateFrom . PHP_EOL;
        $output .= 'van ';
        $output .= $intlStartTimeFrom;
        $output .= ' tot ' . $intlEndTimeEnd;

        return $output;
    }

    protected function formatMoreDays($dateFrom, $dateEnd)
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlWeekDayFrom = $this->fmtWeekDayLong->format($dateFrom);
        $intlStartTimeFrom = $this->fmtTime->format($dateFrom);

        $intlDateEnd = $this->fmt->format($dateEnd);
        $intlWeekDayEnd = $this->fmtWeekDayLong->format($dateEnd);
        $intlEndTimeEnd = $this->fmtTime->format($dateEnd);

        $output = 'Van ';
        $output .= $intlWeekDayFrom . ' ' . $intlDateFrom . ' ' . $intlStartTimeFrom . PHP_EOL;
        $output .= 'tot ';
        $output .= $intlWeekDayEnd . ' ' . $intlDateEnd . ' ' . $intlEndTimeEnd;

        return $output;
    }
}
