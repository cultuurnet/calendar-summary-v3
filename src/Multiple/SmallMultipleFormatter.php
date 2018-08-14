<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

abstract class SmallMultipleFormatter {
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
    public function __construct($langCode, $hidePastDates) {
        $this->langCode = $langCode;
        $this->hidePast = $hidePastDates;
    }
}