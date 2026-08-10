<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Childcare;

final class ChildcareFormatter
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var Childcare
     */
    private $childcare;

    /**
     * @var bool
     */
    private $forEveryDay = false;

    /**
     * @var bool
     */
    private $withBraces = false;

    /**
     * @var bool
     */
    private $capitalize = false;

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
     * Prefixes the childcare with 'elke dag', used when every day has the same childcare.
     */
    public function forEveryDay(): self
    {
        $c = clone $this;
        $c->forEveryDay = true;
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

        if ($this->forEveryDay) {
            $childcareText = $this->translator->translate('every_day') . ' ' . $childcareText;
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
