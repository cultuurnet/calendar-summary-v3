<?php

namespace CultuurNet\CalendarSummaryV3;

use DElfimov\Translate\Translate;
use DElfimov\Translate\Loader\PhpArrayLoader;

class Translator {

    protected $translator;

    public function __construct($language) {

        $messages = [
            'en' => [
                'from' => 'from',
                'till' => 'till',
                'closed' => 'closed'
            ],
            'nl' => [
                'from' => 'van',
                'till' => 'tot',
                'closed' => 'gesloten'
            ],
            'fr' => [
                'from' => 'de',
                'till' => 'à',
                'closed' => 'fermée'
            ],
            'de' => [
                'from' => 'von',
                'till' => 'bis',
                'closed' => 'geschlossen'
            ]
        ];

        $t = new Translate(new PhpArrayLoader($messages),
            [
                "default" => 'en',
                "available" => ['en', 'nl', 'fr', 'de'],
            ]
        );
        $t->setLanguage($language);
    }

}