<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use IntlDateFormatter;
use CultuurNet\CalendarSummaryV3\Translator;

abstract class MediumPeriodicFormatter
{

    /**
     * @var IntlDateFormatter
     */
    protected $fmt;

    /**
     * @var IntlDateFormatter
     */
    protected $fmtDay;

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

        $this->fmtDay = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'eeee'
        );

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }
}
