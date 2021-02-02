<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Middleware;

use Closure;
use CultuurNet\CalendarSummaryV3\TranslatedStatusReasonFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\Place;

class NonAvailablePlaceHTMLFormatter implements FormatterMiddleware
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(
        Translator $translator
    ) {
        $this->translator = $translator;
    }


    public function format(Offer $offer, Closure $next): string
    {
        if ($this->appliesToOffer($offer)) {
            $statusText = $status = $offer->getStatus()->getType() === 'Unavailable' ?
                $this->translator->translate('permanently_closed') :
                $this->translator->translate('temporarily_closed');

            $reasonFormatter = new TranslatedStatusReasonFormatter($this->translator);
            $titleAttribute = $reasonFormatter->formatAsTitleAttribute($offer->getStatus());

            return '<span ' . $titleAttribute . 'class="cf-status">' . $statusText . '</span>';
        }

        return $next($offer);
    }

    private function appliesToOffer(Offer $offer): bool
    {
        if (!$offer instanceof Place) {
            return false;
        }

        return in_array($offer->getStatus()->getType(), ['Unavailable', 'TemporarilyUnavailable']);
    }
}
