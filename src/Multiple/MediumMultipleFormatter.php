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
     * @var bool $hidepast
     */
    protected $hidePast;

    public function __construct(string $langCode, bool $hidePastDates)
    {
        $this->langCode = $langCode;
        $this->hidePast = $hidePastDates;
    }
}
