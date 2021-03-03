<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\Status;

final class HtmlStatusFormatter
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var Status
     */
    private $status;

    /**
     * @var string
     */
    private $element = 'span';

    /**
     * @var bool
     */
    private $withBraces = false;

    /**
     * @var bool
     */
    private $capitalize = false;

    /**
     * @var bool
     */
    private $isPlace = false;

    private function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public static function forOffer(Offer $offer, Translator $translator): self
    {
        $formatter = new self($translator);
        $formatter->status = $offer->getStatus();
        $formatter->isPlace = $offer->isPlace();
        return $formatter;
    }

    public function withBraces(): self
    {
        $c = clone $this;
        $c->withBraces = true;
        return $c;
    }

    public function withoutBraces(): self
    {
        $c = clone $this;
        $c->withBraces = false;
        return $c;
    }

    public function withElement(string $element): self
    {
        $c = clone $this;
        $c->element = $element;
        return $c;
    }

    public function capitalize(): self
    {
        $c = clone $this;
        $c->capitalize = true;
        return $c;
    }

    public function toString(): string
    {
        if ($this->status->getType() === 'Available') {
            return '';
        }

        $openTag = '<' . $this->element . ' ' . $this->getReasonAsTitleAttribute() . 'class="cf-status">';
        $closingTag = '</' . $this->element . '>';
        $statusText = $this->getStatusText();

        if ($this->capitalize) {
            $statusText = ucfirst($statusText);
        }

        if ($this->withBraces) {
            $statusText = '(' . $statusText . ')';
        }

        return $openTag . $statusText . $closingTag;
    }

    private function getStatusText(): string
    {
        if ($this->isPlace) {
            return $this->status->getType() === 'Unavailable' ?
                $this->translator->translate('permanently_closed') :
                $this->translator->translate('temporarily_closed');
        }

        return $this->status->getType() === 'Unavailable' ?
            $this->translator->translate('cancelled') :
            $this->translator->translate('postponed');
    }

    private function getReasonAsTitleAttribute(): string
    {
        $translatedReason = $this->status->getReasonForLanguage($this->translator->getLanguageCode());

        if (empty($translatedReason)) {
            return '';
        }

        return 'title="' . $translatedReason . '" ';
    }
}
