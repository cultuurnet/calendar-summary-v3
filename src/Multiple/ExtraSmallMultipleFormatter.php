<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Translator;

abstract class ExtraSmallMultipleFormatter
{
    /**
     * @var string
     */
    protected $langCode;

    /**
     * @var Translator
     */
    protected $trans;

    public function __construct(string $langCode)
    {
        $this->langCode = $langCode;
        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }
}
