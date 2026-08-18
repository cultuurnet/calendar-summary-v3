<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\Offer;

/**
 * Renders the childcare and the overnight stay of a (sub)event.
 */
final class HtmlChildcareFormatter
{
    private Translator $translator;

    private ?Childcare $childcare = null;

    private bool $overnight = false;

    private bool $asNestedList = false;

    private bool $alsoWhenThereIsNone = false;

    private function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public static function forOffer(Offer $offer, Translator $translator): self
    {
        $formatter = new self($translator);
        $formatter->childcare = $offer->getChildcare();
        $formatter->overnight = $offer->hasOvernight();
        return $formatter;
    }

    /**
     * Renders the childcare as a list of its own, to nest inside the list item of the date.
     */
    public function asNestedList(): self
    {
        $c = clone $this;
        $c->asNestedList = true;
        return $c;
    }

    /**
     * Mentions that there is no childcare instead of staying silent about it, for when other
     * dates of the same offer do have one and its absence here is worth reporting.
     */
    public function alsoWhenThereIsNone(): self
    {
        $c = clone $this;
        $c->alsoWhenThereIsNone = true;
        return $c;
    }

    public function toString(): string
    {
        $childcareText = $this->getChildcareText();

        if ($childcareText === '') {
            return '';
        }

        if ($this->asNestedList) {
            return '<ul class="list-unstyled">'
                . '<li class="cf-childcare">' . $childcareText . '</li>'
                . '</ul>';
        }

        return '<span class="cf-childcare">' . $childcareText . '</span>';
    }

    /**
     * A nested list is a block of its own and therefore starts a sentence of its own, between
     * braces. Inline it continues the sentence of the date it follows and needs neither.
     */
    private function getChildcareText(): string
    {
        if ($this->childcare === null) {
            $parts = [];

            if ($this->overnight) {
                $parts[] = $this->translator->translate('overnight');
            }

            if ($this->alsoWhenThereIsNone) {
                $parts[] = $this->translator->translate('no_childcare');
            }

            if ($parts === []) {
                return '';
            }

            $text = implode(', ', $parts);

            return $this->asNestedList ? '(' . ucfirst($text) . ')' : $text;
        }

        $formatter = ChildcareFormatter::forChildcare($this->childcare, $this->translator);

        if ($this->overnight) {
            $formatter = $formatter->precededBy($this->translator->translate('overnight') . ',');
        }

        if (!$this->asNestedList) {
            return $formatter->toString();
        }

        return $formatter->capitalize()->withBraces()->toString();
    }
}
