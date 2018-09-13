<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\Translator;
use IntlDateFormatter;

abstract class LargePermanentFormatter {
    /**
     * Translate the day to Dutch.
     */
    protected $mappingDays = array(
        'monday' => 'maandag',
        'tuesday' => 'dinsdag',
        'wednesday' => 'woensdag',
        'thursday' => 'donderdag',
        'friday' => 'vrijdag',
        'saturday' => 'zaterdag',
        'sunday' => 'zondag',
    );

    /**
     * Translate the day to short Dutch format.
     */
    protected $mappingShortDays = array(
        'monday' => 'Mo',
        'tuesday' => 'Tu',
        'wednesday' => 'We',
        'thursday' => 'Th',
        'friday' => 'Fr',
        'saturday' => 'Sa',
        'sunday' => 'Su',
    );

    protected $fmtDays;

    protected $fmtShortDays;

    protected $trans;

    public function __construct($langCode) {
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