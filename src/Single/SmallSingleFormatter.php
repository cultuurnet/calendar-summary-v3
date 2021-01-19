<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\Translator;
use IntlDateFormatter;

abstract class SmallSingleFormatter
{
    /**
     * @var IntlDateFormatter
     */
    protected $fmtDay;

    /**
     * @var IntlDateFormatter
     */
    protected $fmtMonth;

    /**
     * @var Translator
     */
    protected $trans;

    public function __construct(string $langCode)
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
            'MMM'
        );

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }
}
