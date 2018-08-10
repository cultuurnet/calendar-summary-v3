<?php
/**
 * Created by PhpStorm.
 * User: stijnswaanen
 * Date: 08/08/2018
 * Time: 11:52
 */

namespace CultuurNet\CalendarSummaryV3\Periodic;

use IntlDateFormatter;
use DElfimov\Translate\Translate;
use DElfimov\Translate\Loader\PhpFilesLoader;

abstract class MediumPeriodicFormatter {

    /**
     * @var IntlDateFormatter
     */
    protected $fmt;

    /**
     * @var IntlDateFormatter
     */
    protected $fmtDay;

    protected $trans;

    /**
     * @var string $langCode
     *
     * MediumPeriodicHTMLFormatter constructor.
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

        $this->fmtDay = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'eeee'
        );

        $this->trans = new Translate(
            new PhpFilesLoader(realpath(__DIR__ . '/../Translations')),
            [
                'default' => 'en',
                'available' => ['en', 'nl', 'fr', 'de'],
            ]
        );

        $this->trans->setLanguage(substr($langCode, 0, 2));
    }

}