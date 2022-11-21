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
                'from_hour' => 'from',
                'till' => 'till',
                'till_hour' => 'till',
                'closed' => 'closed',
                'open' => 'open at',
                'at' => 'at',
                'open_every_day' => 'Open every day',
                'and' => 'and',
                'permanently_closed' => 'Permanently closed',
                'temporarily_closed' => 'Temporarily closed',
                'cancelled' => 'cancelled',
                'postponed' => 'postponed',
                'event_concluded' => 'Event ended',
                'sold_out' => 'Sold out or fully booked',
                'this' => 'This',
                'today' => 'Today',
                'tonight' => 'Tonight',
                'tomorrow' => 'Tomorrow',
                'to' => 'to',
            ],
            'nl' => [
                'from' => 'van',
                'from_period' => 'vanaf',
                'from_hour' => 'van',
                'till' => 'tot',
                'till_hour' => 'tot',
                'closed' => 'gesloten',
                'open' => 'open op',
                'at' => 'om',
                'open_every_day' => 'Elke dag open',
                'and' => 'en',
                'permanently_closed' => 'Permanent gesloten',
                'temporarily_closed' => 'Tijdelijk gesloten',
                'cancelled' => 'geannuleerd',
                'postponed' => 'uitgesteld',
                'event_concluded' => 'Evenement afgelopen',
                'sold_out' => 'Volzet of uitverkocht',
                'this' => 'Deze',
                'today' => 'Vandaag',
                'tonight' => 'Vanavond',
                'tomorrow' => 'Morgen',
                'to' => 'tot en met',
            ],
            'fr' => [
                'from' => 'du',
                'from_period' => 'du',
                'from_hour' => 'de',
                'till' => 'au',
                'till_hour' => 'à',
                'closed' => 'fermé',
                'open' => 'ouvert le',
                'at' => 'à',
                'open_every_day' => 'Ouvert tous les jours',
                'and' => 'et',
                'permanently_closed' => 'Fermé définitivement',
                'temporarily_closed' => 'Fermé temporairement',
                'cancelled' => 'annulé',
                'postponed' => 'reporté',
                'event_concluded' => 'Fin de l’événement',
                'sold_out' => 'Complet',
                'this' => 'Ce',
                'today' => 'Aujourd\'hui',
                'tonight' => 'Ce soir',
                'tomorrow' => 'Demain',
                'to' => 'à',
            ],
            'de' => [
                'from' => 'von',
                'from_period' => 'aus',
                'from_hour' => 'von',
                'till' => 'bis',
                'till_hour' => 'bis',
                'closed' => 'geschlossen',
                'open' => 'öffnen',
                'at' => 'um',
                'open_every_day' => 'Jeden Tag geöffnet',
                'and' => 'und',
                'permanently_closed' => 'Dauerhaft geschlossen',
                'temporarily_closed' => 'Vorübergehend geschlossen',
                'cancelled' => 'abgesagt',
                'postponed' => 'verschoben',
                'event_concluded' => 'Event abgeschlossen',
                'sold_out' => 'Ausgebucht oder ausverkauft',
                'this' => 'Diesen',
                'today' => 'Heute',
                'tonight' => 'Diesen Abend',
                'tomorrow' => 'Morgen',
                'to' => 'bis',
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
