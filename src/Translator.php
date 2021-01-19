<?php

namespace CultuurNet\CalendarSummaryV3;

use DElfimov\Translate\Translate;
use DElfimov\Translate\Loader\PhpArrayLoader;

class Translator
{
    /**
     * @var Translate
     */
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
                'at' => 'at',
                'always_open' => 'always open',
                'and' => 'and'
            ],
            'nl' => [
                'from' => 'van',
                'from_period' => 'vanaf',
                'till' => 'tot',
                'closed' => 'gesloten',
                'open' => 'open op',
                'at' => 'om',
                'always_open' => 'altijd open',
                'and' => 'en'
            ],
            'fr' => [
                'from' => 'du',
                'from_period' => 'du',
                'till' => 'au',
                'closed' => 'fermé',
                'open' => 'ouvert le',
                'at' => 'à',
                'always_open' => 'toujours ouvert',
                'and' => 'et'
            ],
            'de' => [
                'from' => 'von',
                'from_period' => 'aus',
                'till' => 'bis',
                'closed' => 'geschlossen',
                'open' => 'öffnen',
                'at' => 'um',
                'always_open' => 'immer offen',
                'and' => 'und'
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

    public function setLanguage($langCode): void
    {
        $this->translator->setLanguage(substr($langCode, 0, 2));
    }

    public function getTranslations(): Translate
    {
        return $this->translator;
    }
}
