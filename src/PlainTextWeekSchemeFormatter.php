<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHours;
use DateTimeImmutable;

/**
 * Renders the opening hours of a week as plain text.
 */
final class PlainTextWeekSchemeFormatter
{
    private DateFormatter $formatter;

    private Translator $translator;

    private OpeningHours $openingHours;

    private bool $asSingleLine = false;

    private bool $withChildcare = false;

    private function __construct(Translator $translator)
    {
        $this->formatter = new DateFormatter($translator->getLocale());
        $this->translator = $translator;
    }

    public static function forOpeningHours(OpeningHours $openingHours, Translator $translator): self
    {
        $formatter = new self($translator);
        $formatter->openingHours = $openingHours;
        return $formatter;
    }

    /**
     * Lists only the days that have opening hours, on a single line between braces.
     * Without it every day of the week gets its own line and the days without
     * opening hours are marked as closed.
     */
    public function asSingleLine(): self
    {
        $c = clone $this;
        $c->asSingleLine = true;
        return $c;
    }

    /**
     * Mentions the childcare of the opening hours.
     */
    public function withChildcare(): self
    {
        $c = clone $this;
        $c->withChildcare = true;
        return $c;
    }

    public function toString(): string
    {
        // Without opening hours there is no week scheme to show. Saying nothing beats
        // an empty '()' or a week of closed days, which would claim the place is never
        // open while empty opening hours mean the opposite.
        if ($this->openingHours->isEmpty()) {
            return '';
        }

        // When every day has the same childcare it is summarized in a single line
        // instead of being repeated on every day.
        $sharedChildcare = $this->withChildcare ? $this->openingHours->sharedChildcare() : null;

        $timespansPerDay = $this->timespansPerDay();

        $lines = $this->asSingleLine
            ? $this->formatAsSingleLine($timespansPerDay, $sharedChildcare)
            : $this->formatAsALinePerDay($timespansPerDay, $sharedChildcare);

        if ($sharedChildcare !== null) {
            $lines[] = ' ' . ChildcareFormatter::forChildcare($sharedChildcare, $this->translator)
                ->forEveryDay()
                ->withBraces()
                ->toString();
        }

        return implode(PHP_EOL, $lines);
    }

    /**
     * @param array<string, OpeningHour[]> $timespansPerDay
     * @return string[]
     */
    private function formatAsSingleLine(array $timespansPerDay, ?Childcare $sharedChildcare): array
    {
        $formattedDays = [];
        foreach ($timespansPerDay as $dayOfWeek => $timespans) {
            $formattedDays[] = $this->formatDay($dayOfWeek, $timespans)
                ->lowercaseNextFirstCharacter()
                ->toString();
        }

        $lines = ['(' . implode(', ', $formattedDays) . ')'];

        // The days share a single line, so their childcare cannot follow them
        // directly and needs to mention the day it applies to.
        if ($sharedChildcare === null) {
            foreach ($timespansPerDay as $dayOfWeek => $timespans) {
                $lines = array_merge($lines, $this->childcareLines($dayOfWeek, $timespans, true));
            }
        }

        return $lines;
    }

    /**
     * @param array<string, OpeningHour[]> $timespansPerDay
     * @return string[]
     */
    private function formatAsALinePerDay(array $timespansPerDay, ?Childcare $sharedChildcare): array
    {
        $lines = [];

        foreach (OpeningHour::ALLOWED_DAYS as $dayOfWeek) {
            $timespans = $timespansPerDay[$dayOfWeek] ?? [];

            $lines[] = $this->formatDay($dayOfWeek, $timespans)->toString();

            if ($sharedChildcare === null) {
                $lines = array_merge($lines, $this->childcareLines($dayOfWeek, $timespans, false));
            }
        }

        return $lines;
    }

    /**
     * @param OpeningHour[] $timespans
     */
    private function formatDay(string $dayOfWeek, array $timespans): PlainTextSummaryBuilder
    {
        $day = PlainTextSummaryBuilder::start($this->translator)
            ->append($this->translateDayOfWeek($dayOfWeek));

        if (!$timespans) {
            return $day->closed();
        }

        foreach ($timespans as $timespan) {
            $day = $day
                ->fromHour(OpeningHourFormatter::format($timespan->getOpens()))
                ->tillHour(OpeningHourFormatter::format($timespan->getCloses()));
        }

        return $day;
    }

    /**
     * @param OpeningHour[] $timespans
     * @return string[]
     */
    private function childcareLines(string $dayOfWeek, array $timespans, bool $withDayOfWeek): array
    {
        if (!$this->withChildcare) {
            return [];
        }

        // Only when the timespans of this day have a different childcare it is
        // repeated after every single one of them.
        $childcareOfDay = $this->openingHours->onDayOfWeek($dayOfWeek)->sharedChildcare();
        if ($childcareOfDay !== null) {
            return [$this->childcareLine($childcareOfDay, $withDayOfWeek ? $dayOfWeek : '')];
        }

        $lines = [];
        foreach ($timespans as $timespan) {
            $childcare = $timespan->getChildcare();
            if ($childcare !== null) {
                $lines[] = $this->childcareLine($childcare, $withDayOfWeek ? $dayOfWeek : '');
            }
        }

        return $lines;
    }

    /**
     * Childcare gets a line of its own, indented with a single space and between braces.
     */
    private function childcareLine(Childcare $childcare, string $dayOfWeek): string
    {
        $childcareFormatter = ChildcareFormatter::forChildcare($childcare, $this->translator)->withBraces();

        if ($dayOfWeek !== '') {
            $childcareFormatter = $childcareFormatter->precededBy($this->translateDayOfWeek($dayOfWeek));
        }

        return ' ' . $childcareFormatter->toString();
    }

    /**
     * @return array<string, OpeningHour[]>
     */
    private function timespansPerDay(): array
    {
        $timespansPerDay = [];

        foreach ($this->openingHours as $openingHour) {
            foreach ($openingHour->getDaysOfWeek() as $dayOfWeek) {
                $timespansPerDay[$dayOfWeek][] = $openingHour;
            }
        }

        return $timespansPerDay;
    }

    private function translateDayOfWeek(string $dayOfWeek): string
    {
        return $this->formatter->formatAsDayOfWeek(new DateTimeImmutable($dayOfWeek));
    }
}
