<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use IntlDateFormatter;

abstract class MediumSingleFormatter
{
    protected $fmt;

    protected $fmtDay;

    public function __construct($langCode)
    {
        $this->fmt = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );

        $this->fmtDay = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'eeee'
        );
    }
}
