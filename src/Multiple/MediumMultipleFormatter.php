<?php
/**
 * Created by PhpStorm.
 * User: stijnswaanen
 * Date: 08/08/2018
 * Time: 12:02
 */

namespace CultuurNet\CalendarSummaryV3\Multiple;

abstract class MediumMultipleFormatter
{
    /**
     * @var string $langCode
     */
    protected $langCode;

    /**
     * @var boolean $hidepast
     */
    protected $hidePast;

    /**
     * @var string $langCode
     * @var boolean $hidePastDates
     *
     * MediumMultipleHTMLFormatter constructor.
     */
    public function __construct($langCode, $hidePastDates)
    {
        $this->langCode = $langCode;
        $this->hidePast = $hidePastDates;
    }
}
