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
    private $translator;

    public function __construct(
        Translator $translator
    ) {
        $this->translator = $translator;
    }


    public function format(Offer $offer, Closure $next): string
    {
        if ($this->appliesToOffer($offer)) {
            $status = $offer->getStatus()->getType();
            $reason = $offer->getStatus()->getReason();

            return $this->wrapInTag(
                $status === 'Unavailable' ?
                    $this->translator->translate('permanently_closed') :
                    $this->translator->translate('temporarily_closed'),
                $reason ? $reason->getValueForLanguage($this->translator->getLanguageCode()) : ''
            );
        }

        return $next($offer);
    }

    private function wrapInTag(string $text, string $reason): string
    {
        $titleAttribute = '';

        if (!empty($reason)) {
            $titleAttribute = 'title="' . $reason . '" ';
        }

        return '<span ' . $titleAttribute . 'class="cf-meta">' . $text . '</span>';
    }

    private function appliesToOffer(Offer $offer): bool
    {
        if (!$offer instanceof Place) {
            return false;
        }

        return in_array($offer->getStatus()->getType(), ['Unavailable', 'TemporarilyUnavailable']);
    }
}
