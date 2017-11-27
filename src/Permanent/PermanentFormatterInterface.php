<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\Place;

interface PermanentFormatterInterface
{

    /**
     * @param Place $place
     * @return string
     */
    public function format(Place $place);
}
