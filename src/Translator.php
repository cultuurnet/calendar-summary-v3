<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use DElfimov\Translate\Translate;
use DElfimov\Translate\Loader\PhpArrayLoader;

final class Translator
{
    /**
     * @var Translate
     */
    private $translator;

    /**
     * @var string
     */
    private $locale;

    public function __construct(string $locale)
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
                'and' => 'and',
                'permanently_closed' => 'Permanently closed',
                'temporarily_closed' => 'Temporarily closed',
                'cancelled' => 'cancelled',
                'postponed' => 'postponed',
                'event_concluded' => 'This event has finished',
            ],
            'nl' => [
                'from' => 'van',
                'from_period' => 'vanaf',
                'till' => 'tot',
                'closed' => 'gesloten',
                'open' => 'open op',
                'at' => 'om',
                'always_open' => 'altijd open',
                'and' => 'en',
                'permanently_closed' => 'Permanent gesloten',
                'temporarily_closed' => 'Tijdelijk gesloten',
                'cancelled' => 'geannuleerd',
                'postponed' => 'uitgesteld',
                'event_concluded' => 'Dit evenement is afgelopen',
            ],
            'fr' => [
                'from' => 'du',
                'from_period' => 'du',
                'till' => 'au',
                'closed' => 'fermé',
                'open' => 'ouvert le',
                'at' => 'à',
                'always_open' => 'toujours ouvert',
                'and' => 'et',
                'permanently_closed' => 'Fermé définitivement',
                'temporarily_closed' => 'Fermé temporairement',
                'cancelled' => 'annulé',
                'postponed' => 'reporté',
                'event_concluded' => 'Cet evenement est fini',
            ],
            'de' => [
                'from' => 'von',
                'from_period' => 'aus',
                'till' => 'bis',
                'closed' => 'geschlossen',
                'open' => 'öffnen',
                'at' => 'um',
                'always_open' => 'immer offen',
                'and' => 'und',
                'permanently_closed' => 'Dauerhaft geschlossen',
                'temporarily_closed' => 'Vorübergehend geschlossen',
                'cancelled' => 'abgesagt',
                'postponed' => 'verschoben',
                'event_concluded' => 'Dieses event ist geschlossen',
            ],
        ];

        $this->translator = new Translate(
            new PhpArrayLoader($messages),
            [
                'default' => 'en',
                'available' => ['en', 'nl', 'fr', 'de'],
            ]
        );

        $this->locale = $locale;
        $this->translator->setLanguage($this->getLanguageCode());
    }

    public function translate(string $key): string
    {
        return $this->translator->t($key);
    }

    /**
     * e.g. 'nl_BE'
     */
    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * e.g. 'nl'
     */
    public function getLanguageCode(): string
    {
        return substr($this->locale, 0, 2);
    }
}
