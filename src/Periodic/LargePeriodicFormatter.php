<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use IntlDateFormatter;
use CultuurNet\CalendarSummaryV3\Translator;

abstract class LargePeriodicFormatter
{

    /**
     * @var IntlDateFormatter
     */
    protected $fmt;

    /**
     * @var IntlDateFormatter
     */
    protected $fmtDays;

    /**
     * @var IntlDateFormatter
     */
    protected $fmtShortDays;

    /**
     * @var Translator
     */
    protected $trans;

    /**
     * weekdays
     */
    protected $daysOfWeek = array(
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday'
    );

    /**
     * @var string $langCode
     *
     * LargePeriodicHTMLFormatter constructor.
     */
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

        $this->fmtDays = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'EEEE'
        );

        $this->fmtShortDays = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'EEE'
        );

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }
}
