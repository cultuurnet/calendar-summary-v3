<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Offer;

/**
 * Provides a formatter for formatting single events in large plain text format.
 */
class LargeSinglePlainTextFormatter extends LargeSingleFormatter implements SingleFormatterInterface
{

    /**
     * {@inheritdoc}
     */
    public function format(Offer $offer)
    {
        $dateFrom = $offer->getStartDate();
        $dateEnd = $offer->getEndDate();

        if ($dateFrom->format('Y-m-d') == $dateEnd->format('Y-m-d')) {
            $output = $this->formatSameDay($dateFrom, $dateEnd);
        } else {
            $output = $this->formatMoreDays($dateFrom, $dateEnd);
        }

        return $output;
    }

    protected function formatSameDay($dateFrom, $dateEnd)
    {
        $intlDateFrom = $this->fmt->format($dateFrom);
        $intlWeekDayFrom = $this->fmtWeekDayLong->format($dateFrom);
        $intlStartTimeFrom = $this->fmtTime->format($dateFrom);

        $intlEndTimeEnd = $this->fmtTime->format($dateEnd);

        $output = $intlWeekDayFrom . ' ' . $intlDateFrom;
        $output .= ' van ';
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
        $output .= $intlWeekDayFrom . ' ' . $intlDateFrom . ' ' . $intlStartTimeFrom;
        $output .= ' tot ';
        $output .= $intlWeekDayEnd . ' ' . $intlDateEnd . ' ' . $intlEndTimeEnd;

        return $output;
    }
}
