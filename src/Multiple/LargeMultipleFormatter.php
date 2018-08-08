<?php
/**
 * Created by PhpStorm.
 * User: stijnswaanen
 * Date: 08/08/2018
 * Time: 12:04
 */

namespace CultuurNet\CalendarSummaryV3\Multiple;

abstract class LargeMultipleFormatter {
    /**
     * @var string $langCode
     */
    protected $langCode;

    /**
     * @var string $langCode
     *
     * LargeMultipleHTMLFormatter constructor.
     */
    public function __construct($langCode) {
        $this->langCode = $langCode;
    }
}