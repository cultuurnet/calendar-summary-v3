<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Translator;

abstract class ExtraSmallMultipleFormatter
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
     * @var Translator
     */
    protected $trans;

    public function __construct(string $langCode, bool $hidePastDates)
    {
        $this->langCode = $langCode;
        $this->hidePast = $hidePastDates;
        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }
}
