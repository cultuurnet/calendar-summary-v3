<?php
/**
 * Created by PhpStorm.
 * User: stijnswaanen
 * Date: 08/08/2018
 * Time: 11:48
 */

namespace CultuurNet\CalendarSummaryV3\Periodic;

use IntlDateFormatter;

abstract class ExtraSmallPeriodicFormatter {

    /**
     * @var IntlDateFormatter
     */
    protected $fmtDay;

    /**
     * @var IntlDateFormatter
     */
    protected $fmtMonth;

    /**
     * @var string $langCode
     *
     * ExtraSmallPeriodicHTMLFormatter constructor.
     */
    public function __construct($langCode)
    {
        $this->fmtDay = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'd'
        );

        $this->fmtMonth = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'M'
        );
    }
}