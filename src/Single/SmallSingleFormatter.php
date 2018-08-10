<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use DElfimov\Translate\Translate;
use DElfimov\Translate\Loader\PhpFilesLoader;
use IntlDateFormatter;

abstract class SmallSingleFormatter
{
    protected $fmtDay;

    protected $fmtMonth;
    /**
     * @var \CultuurNet\CalendarSummaryV3\Translator
     */
    protected $trans;

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
            'MMM'
        );

        $this->trans = new Translate(
            new PhpFilesLoader(realpath(__DIR__ . '/../Translations')),
            [
                'default' => 'en',
                'available' => ['en', 'nl', 'fr', 'de'],
            ]
        );

        $this->trans->setLanguage(substr($langCode, 0, 2));
    }
}
