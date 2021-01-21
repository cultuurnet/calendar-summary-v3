<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

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
    private $uppercaseNextFirstLineCharacter;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->lines = [];
        $this->workingLine = [];
        $this->uppercaseNextFirstLineCharacter = true;
    }

    public function addTranslation(string $translationKey): self
    {
        $c = clone $this;
        $c->workingLine[] = $this->translator->getTranslations()->t($translationKey);
        return $c;
    }

    public function openAt(string ...$text): self
    {
        return $this->addTranslation('open')->addMultiple($text, ', ');
    }

    public function alwaysOpen(): self
    {
        return $this->addTranslation('always_open');
    }

    public function closed(): self
    {
        return $this->addTranslation('closed');
    }

    public function from(string ...$text): self
    {
        return $this->addTranslation('from')->addMultiple($text);
    }

    public function till(string ...$text): self
    {
        return $this->addTranslation('till')->addMultiple($text);
    }

    public function at(string ...$text): self
    {
        return $this->addTranslation('at')->addMultiple($text);
    }

    public function add(string $text): self
    {
        $c = clone $this;
        $c->workingLine[] = $text;
        return $c;
    }

    public function addMultiple(array $text, string $separator = ' '): self
    {
        $c = clone $this;
        $c->workingLine[] = implode($separator, $text);
        return $c;
    }

    public function startNewLine(): self
    {
        $c = clone $this;
        $c->completeLine();
        $c->uppercaseNextFirstLineCharacter = true;
        return $c;
    }

    public function startNewLineWithLowercaseFirstCharacter(): self
    {
        $c = clone $this;
        $c->completeLine();
        $c->uppercaseNextFirstLineCharacter = false;
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

    private function concatenateLines(): string
    {
        return implode(PHP_EOL, $this->lines);
    }

    private function completeLine(): void
    {
        $completedLine = implode(' ', $this->workingLine);
        $completedLine = $this->uppercaseNextFirstLineCharacter ? ucfirst($completedLine) : lcfirst($completedLine);

        $this->lines[] = $completedLine;
        $this->workingLine = [];
    }
}
