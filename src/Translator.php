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
                'till_included' => 'till',
                'till_hour' => 'till',
                'closed' => 'closed',
                'open_at' => 'open at',
                'open_at_consecutive_days' => 'open at',
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
                'open_every' => 'Open every',
                'open_every_end' => '',
            ],
            'nl' => [
                'from' => 'van',
                'from_period' => 'vanaf',
                'from_hour' => 'van',
                'till' => 'tot',
                'till_included' => 'tot en met',
                'till_hour' => 'tot',
                'closed' => 'gesloten',
                'open_at' => 'open op',
                'open_at_consecutive_days' => 'open van',
                'at' => 'om',
                'open_every_day' => 'Alle dagen open',
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
                'open_every' => 'Elke',
                'open_every_end' => 'open',
            ],
            'fr' => [
                'from' => 'du',
                'from_period' => 'du',
                'from_hour' => 'de',
                'till' => 'au',
                'till_included' => 'au',
                'till_hour' => 'à',
                'closed' => 'fermé',
                'open_at' => 'ouvert le',
                'open_at_consecutive_days' => 'ouvert le',
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
                'open_every' => 'Ouvert chaque',
                'open_every_end' => '',
            ],
            'de' => [
                'from' => 'von',
                'from_period' => 'aus',
                'from_hour' => 'von',
                'till' => 'bis',
                'till_included' => 'bis',
                'till_hour' => 'bis',
                'closed' => 'geschlossen',
                'open_at' => 'öffnen',
                'open_at_consecutive_days' => 'öffnen',
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
                'open_every' => 'Jeden',
                'open_every_end' => 'geöffnet',
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
