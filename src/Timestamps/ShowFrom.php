<?php

namespace CultuurNet\CalendarSummaryV3\Timestamps;

trait ShowFrom
{
    /**
     * @var int
     */
    private $fromTimestamp;

    /**
     * Make it possible to set a timestamp to only show the calendar info after that date.
     * Useful for making the unit tests independent of the real time.
     *
     * @param int $fromTimestamp
     */
    public function setShowFrom($fromTimestamp)
    {
        if (!is_int($fromTimestamp)) {
            throw new \InvalidArgumentException(
                'The timestamp to start showing calendar info from needs to be of type int.'
            );
        }

        $this->fromTimestamp = $fromTimestamp;
    }

    /**
     * @return int
     */
    public function getShowFrom()
    {
        if ($this->fromTimestamp) {
            return $this->fromTimestamp;
        } else {
            return strtotime(date('Y-m-d') . ' 00:00:00');
        }
    }
}
