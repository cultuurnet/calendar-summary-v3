<?php

namespace CultuurNet\CalendarSummaryV3;

use DElfimov\Translate\Translate;
use DElfimov\Translate\Loader\PhpArrayLoader;

class Translator
{

    protected $translator;

    public function __construct()
    {

        $messages = [
            'en' => [
                'from' => 'from',
                'from_period' => 'from',
                'till' => 'till',
                'closed' => 'closed',
                'open' => 'open at',
                'always_open' => 'always open'
            ],
            'nl' => [
                'from' => 'van',
                'from_period' => 'vanaf',
                'till' => 'tot',
                'closed' => 'gesloten',
                'open' => 'open op',
                'always_open' => 'altijd open'
            ],
            'fr' => [
                'from' => 'de',
                'from_period' => 'de',
                'till' => 'à',
                'closed' => 'fermée',
                'open' => 'ouvert à',
                'always_open' => 'toujours ouvert'
            ],
            'de' => [
                'from' => 'von',
                'from_period' => 'aus',
                'till' => 'bis',
                'closed' => 'geschlossen',
                'open' => 'öffnen',
                'always_open' => 'immer offen'
            ]
        ];

        $this->translator = new Translate(
            new PhpArrayLoader($messages),
            [
                "default" => 'en',
                "available" => ['en', 'nl', 'fr', 'de'],
            ]
        );
    }

    public function setLanguage($langCode)
    {
        $this->translator->setLanguage(substr($langCode, 0, 2));
    }

    public function getTranslations()
    {
        return $this->translator;
    }
}
