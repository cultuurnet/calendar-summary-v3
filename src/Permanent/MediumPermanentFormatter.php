<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\Translator;
use IntlDateFormatter;

abstract class MediumPermanentFormatter
{

    protected $mappingDays = array(
        'monday' => 'maandag',
        'tuesday' => 'dinsdag',
        'wednesday' => 'woensdag',
        'thursday' => 'donderdag',
        'friday' => 'vrijdag',
        'saturday' => 'zaterdag',
        'sunday' => 'zondag',
    );

    protected $fmtDays;

    protected $fmtShortDays;

    protected $trans;

    public function __construct($langCode)
    {
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
