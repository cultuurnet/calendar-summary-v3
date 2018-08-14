<?php
/**
 * Created by PhpStorm.
 * User: stijnswaanen
 * Date: 08/08/2018
 * Time: 11:53
 */

namespace CultuurNet\CalendarSummaryV3\Periodic;

use IntlDateFormatter;
use CultuurNet\CalendarSummaryV3\Translator;

abstract class LargePeriodicFormatter {

    /**
     * @var IntlDateFormatter
     */
    protected $fmt;

    protected $trans;

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

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }
}