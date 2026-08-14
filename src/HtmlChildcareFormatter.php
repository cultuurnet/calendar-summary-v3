<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Offer;

/**
 * Renders the childcare and the overnight stay of a (sub)event between braces.
 */
final class HtmlChildcareFormatter
{
    private ChildcareFormatter $childcareFormatter;

    private bool $asNestedList = false;

    private function __construct(ChildcareFormatter $childcareFormatter)
    {
        $this->childcareFormatter = $childcareFormatter;
    }

    public static function forOffer(Offer $offer, Translator $translator): self
    {
        return new self(
            ChildcareFormatter::forOffer($offer, $translator)->capitalize()->withBraces()
        );
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
        $childcareText = $this->childcareFormatter->toString();

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
}
