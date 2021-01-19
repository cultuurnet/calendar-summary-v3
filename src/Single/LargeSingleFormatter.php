<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use IntlDateFormatter;
use CultuurNet\CalendarSummaryV3\Translator;

abstract class LargeSingleFormatter
{
    /**
     * @var IntlDateFormatter
     */
    protected $fmt;

    /**
     * @var IntlDateFormatter
     */
    protected $fmtWeekDayLong;

    /**
     * @var IntlDateFormatter
     */
    protected $fmtTime;

    /**
     * @var Translator
     */
    protected $trans;

    public function __construct(string $langCode)
    {
        $this->fmt = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd MMMM yyyy'
        );

        $this->fmtWeekDayLong = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'EEEE'
        );

        $this->fmtTime = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'HH:mm'
        );

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }
}
