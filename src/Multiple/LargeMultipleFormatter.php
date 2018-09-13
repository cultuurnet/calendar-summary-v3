<?php
/**
 * Created by PhpStorm.
 * User: stijnswaanen
 * Date: 08/08/2018
 * Time: 12:04
 */

namespace CultuurNet\CalendarSummaryV3\Multiple;

abstract class LargeMultipleFormatter
{
    /**
     * @var string $langCode
     */
    protected $langCode;

    /**
     * @var boolean $hidePast
     */
    protected $hidePast;

    /**
     * @var string $langCode
     * @var boolean $hidePastDates
     *
     * LargeMultipleHTMLFormatter constructor.
     */
    public function __construct($langCode, $hidePastDates)
    {
        $this->langCode = $langCode;
        $this->hidePast = $hidePastDates;
    }
}
