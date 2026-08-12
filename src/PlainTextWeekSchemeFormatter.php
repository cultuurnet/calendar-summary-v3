<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

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

    public function toString(): string
    {
        return $this->asSingleLine ? $this->formatAsSingleLine() : $this->formatAsALinePerDay();
    }

    private function formatAsSingleLine(): string
    {
        $formattedDays = [];

        foreach ($this->openingHours as $openingHour) {
            foreach ($openingHour->getDaysOfWeek() as $dayOfWeek) {
                if (!isset($formattedDays[$dayOfWeek])) {
                    $formattedDays[$dayOfWeek] = PlainTextSummaryBuilder::start($this->translator)
                        ->lowercaseNextFirstCharacter()
                        ->append($this->translateDayOfWeek($dayOfWeek));
                } else {
                    $formattedDays[$dayOfWeek] = $formattedDays[$dayOfWeek]->and();
                }

                $formattedDays[$dayOfWeek] = $this->addTimespan($formattedDays[$dayOfWeek], $openingHour);
            }
        }

        return '(' . implode(', ', array_map([$this, 'toLine'], $formattedDays)) . ')';
    }

    private function formatAsALinePerDay(): string
    {
        // Every day of the week is listed, so start from all of them and mark the
        // ones that never get opening hours as closed afterwards.
        $formattedDays = [];
        foreach (OpeningHour::ALLOWED_DAYS as $key => $dayOfWeek) {
            $day = PlainTextSummaryBuilder::start($this->translator);
            if ($key !== 0) {
                $day = $day->startNewLine();
            }

            $formattedDays[$dayOfWeek] = $day->append($this->translateDayOfWeek($dayOfWeek));
        }

        $daysWithOpeningHours = [];
        foreach ($this->openingHours as $openingHour) {
            foreach ($openingHour->getDaysOfWeek() as $dayOfWeek) {
                $daysWithOpeningHours[] = $dayOfWeek;
                $formattedDays[$dayOfWeek] = $this->addTimespan($formattedDays[$dayOfWeek], $openingHour);
            }
        }

        $closedDays = array_diff(OpeningHour::ALLOWED_DAYS, array_unique($daysWithOpeningHours));
        foreach ($closedDays as $closedDay) {
            $formattedDays[$closedDay] = $formattedDays[$closedDay]->closed();
        }

        return implode('', array_map([$this, 'toLine'], $formattedDays));
    }

    private function addTimespan(PlainTextSummaryBuilder $day, OpeningHour $openingHour): PlainTextSummaryBuilder
    {
        return $day
            ->fromHour(OpeningHourFormatter::format($openingHour->getOpens()))
            ->tillHour(OpeningHourFormatter::format($openingHour->getCloses()));
    }

    private function toLine(PlainTextSummaryBuilder $day): string
    {
        return $day->toString();
    }

    private function translateDayOfWeek(string $dayOfWeek): string
    {
        return $this->formatter->formatAsDayOfWeek(new DateTimeImmutable($dayOfWeek));
    }
}
