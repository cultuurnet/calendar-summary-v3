<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Status;

final class PlainTextSummaryBuilder
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var string[]
     */
    private $lines;

    /**
     * @var string[]
     */
    private $workingLine;

    /**
     * @var bool
     */
    private $lowercaseNextFirstCharacter;

    private function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->lines = [];
        $this->workingLine = [];
        $this->lowercaseNextFirstCharacter = false;
    }

    public static function start(Translator $translator): self
    {
        return new self($translator);
    }

    public function openAt(string ...$text): self
    {
        return $this->appendTranslation('open')->appendMultiple($text, ', ');
    }

    public function alwaysOpen(): self
    {
        return $this->appendTranslation('always_open');
    }

    public function closed(): self
    {
        return $this->appendTranslation('closed');
    }

    public function from(string ...$text): self
    {
        return $this->appendTranslation('from')->appendMultiple($text, ' ');
    }

    public function fromPeriod(string ...$text): self
    {
        return $this->appendTranslation('from_period')->appendMultiple($text, ' ');
    }

    public function till(string ...$text): self
    {
        return $this->appendTranslation('till')->appendMultiple($text, ' ');
    }

    public function at(string ...$text): self
    {
        return $this->appendTranslation('at')->appendMultiple($text, ' ');
    }

    public function and(): self
    {
        return $this->appendTranslation('and');
    }

    public function append(string $text): self
    {
        $c = clone $this;
        $c->workingLine[] = $text;
        return $c;
    }

    public function startNewLine(): self
    {
        $c = clone $this;
        $c->completeLine();
        return $c;
    }

    public function lowercaseNextFirstCharacter(): self
    {
        $c = clone $this;
        $c->lowercaseNextFirstCharacter = true;
        return $c;
    }

    public function toString(): string
    {
        // We need to include completeLine() to have the last line included in concatenateLines(), but we do that on a
        // clone because the line shouldn't be completed on this instance where toString() is called or it would be a
        // side-effect.
        $c = clone $this;
        $c->completeLine();
        return $c->concatenateLines();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public static function singleLine(string ...$text): string
    {
        return self::formatLine($text, false);
    }

    private function appendTranslation(string $translationKey): self
    {
        $c = clone $this;
        $c->workingLine[] = $this->translator->translate($translationKey);
        return $c;
    }

    private function appendMultiple(array $text, string $separator): self
    {
        $c = clone $this;
        $c->workingLine[] = implode($separator, $text);
        return $c;
    }

    private function concatenateLines(): string
    {
        return implode(PHP_EOL, $this->lines);
    }

    private function completeLine(): void
    {
        $this->lines[] = self::formatLine($this->workingLine, $this->lowercaseNextFirstCharacter);
        $this->workingLine = [];
        $this->lowercaseNextFirstCharacter = false;
    }

    private static function formatLine(array $line, bool $lowercaseFirstCharacter): string
    {
        $formatted = implode(' ', $line);
        return $lowercaseFirstCharacter ? lcfirst($formatted) : ucfirst($formatted);
    }

    public function appendAvailability(Status $status): self
    {
        $c = clone $this;

        switch ($status->getType()) {
            case 'Unavailable':
                $c->workingLine[] = '(' . $this->translator->translate('cancelled') . ')';
                return $c;
            case 'TemporarilyUnavailable':
                $c->workingLine[] = '(' . $this->translator->translate('postponed') . ')';
                return $c;
            default:
                return $c;
        }
    }
}
