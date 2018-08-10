<?php

namespace CultuurNet\CalendarSummaryV3;

use DElfimov\Translate\Translate;
use DElfimov\Translate\Loader\PhpArrayLoader;


class Translator {

    public function __construct() {

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

        return new Translate(new PhpArrayLoader($messages),
            [
                "default" => 'en',
                "available" => ['en', 'nl', 'fr', 'de'],
            ]
        );
    }

}