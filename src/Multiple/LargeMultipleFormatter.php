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
     * @var bool $hidePast
     */
    protected $hidePast;

    public function __construct(string $langCode, bool $hidePastDates)
    {
        $this->langCode = $langCode;
        $this->hidePast = $hidePastDates;
    }
}
