<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\Offer;

/**
 * Renders the childcare and the overnight stay of a (sub)event between braces.
 */
final class HtmlChildcareFormatter
{
    private Translator $translator;

    private ?Childcare $childcare = null;

    private bool $overnight = false;

    private bool $asNestedList = false;

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

    private function getChildcareText(): string
    {
        if ($this->childcare === null) {
            // Only the first word is capitalized, so an overnight stay without childcare
            // is the only case where the overnight itself starts the sentence on its own.
            return $this->overnight ? '(' . ucfirst($this->translator->translate('overnight')) . ')' : '';
        }

        $formatter = ChildcareFormatter::forChildcare($this->childcare, $this->translator);

        if ($this->overnight) {
            $formatter = $formatter->precededBy($this->translator->translate('overnight') . ',');
        }

        return $formatter->capitalize()->withBraces()->toString();
    }
}
