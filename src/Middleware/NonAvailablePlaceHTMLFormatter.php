<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Middleware;

use Closure;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\Place;

class NonAvailablePlaceHTMLFormatter implements FormatterMiddleware
{
    /**
     * @var Translator
     */
    private $trans;

    public function __construct(
        string $langCode
    ) {
        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

    public function format(Offer $offer, Closure $next): string
    {
        if ($offer instanceof Place) {
            if ($offer->getStatus()->getType() === 'Unavailable') {
                return $this->wrapInTag(
                    $this->trans->getTranslations()->t('permanently_closed')
                );
            }

            if ($offer->getStatus()->getType() === 'TemporarilyUnavailable') {
                return $this->wrapInTag(
                    $this->trans->getTranslations()->t('temporarily_closed')
                );
            }
        }

        return $next($offer);
    }

    private function wrapInTag(string $text): string
    {
        return '<span class="cf-meta">' . $text . '</span>';
    }
}
