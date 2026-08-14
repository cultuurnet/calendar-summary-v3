<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\Offer;

final class ChildcareFormatter
{
    private Translator $translator;

    private ?Childcare $childcare = null;

    private bool $overnight = false;

    private string $prefix = '';

    private bool $withBraces = false;

    private bool $capitalize = false;

    private function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public static function forChildcare(Childcare $childcare, Translator $translator): self
    {
        $formatter = new self($translator);
        $formatter->childcare = $childcare;
        return $formatter;
    }

    /**
     * Combines the childcare of a (sub)event with its overnight stay. Renders nothing
     * when it has neither of them.
     */
    public static function forOffer(Offer $offer, Translator $translator): self
    {
        $formatter = new self($translator);
        $formatter->childcare = $offer->getChildcare();
        $formatter->overnight = $offer->hasOvernight();

        // Only the overnight stay starts the sentence, so the childcare that follows it
        // keeps its lowercase.
        if ($formatter->overnight && $formatter->childcare !== null) {
            return $formatter->precededBy($translator->translate('overnight') . ',');
        }

        return $formatter;
    }

    /**
     * Prefixes the childcare with 'elke dag', used when every day has the same childcare.
     */
    public function forEveryDay(): self
    {
        return $this->precededBy($this->translator->translate('every_day'));
    }

    /**
     * Introduces the childcare with the day(s) it applies to.
     */
    public function precededBy(string $text): self
    {
        $c = clone $this;
        $c->prefix = $text;
        return $c;
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

    public function capitalize(): self
    {
        $c = clone $this;
        $c->capitalize = true;
        return $c;
    }

    public function toString(): string
    {
        $childcareText = $this->getChildcareText();

        if ($childcareText === '') {
            return '';
        }

        if ($this->prefix !== '') {
            $childcareText = $this->prefix . ' ' . $childcareText;
        }

        if ($this->capitalize) {
            $childcareText = ucfirst($childcareText);
        }

        if ($this->withBraces) {
            $childcareText = '(' . $childcareText . ')';
        }

        return $childcareText;
    }

    private function getChildcareText(): string
    {
        if ($this->childcare === null) {
            return $this->overnight ? $this->translator->translate('overnight') : '';
        }

        $start = $this->childcare->getStart();
        $end = $this->childcare->getEnd();

        // Childcare that only happens before the opening hours has no end.
        if ($end === null) {
            return $this->translator->translate('childcare_before')
                . ' ' . $this->translator->translate('childcare_from')
                . ' ' . OpeningHourFormatter::format($start);
        }

        // Childcare that only happens after the opening hours has no start.
        if ($start === null) {
            return $this->translator->translate('childcare_after')
                . ' ' . $this->translator->translate('childcare_till')
                . ' ' . OpeningHourFormatter::format($end);
        }

        return $this->translator->translate('childcare')
            . ' ' . $this->translator->translate('from_hour')
            . ' ' . OpeningHourFormatter::format($start)
            . ' ' . $this->translator->translate('till_hour')
            . ' ' . OpeningHourFormatter::format($end);
    }
}
