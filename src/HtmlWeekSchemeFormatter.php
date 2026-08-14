<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Childcare;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHours;
use DateTimeImmutable;

/**
 * Renders the opening hours of every day of the week as a list.
 */
final class HtmlWeekSchemeFormatter
{
    private DateFormatter $formatter;

    private Translator $translator;

    private OpeningHours $openingHours;

    private bool $withChildcare = false;

    private bool $childcareInNestedList = false;

    private bool $withAdjustedHoursNotice = false;

    private bool $withEveryDayOfTheWeek = false;

    private bool $withHeading = false;

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
     * Mentions the childcare of the opening hours.
     */
    public function withChildcare(): self
    {
        $c = clone $this;
        $c->withChildcare = true;
        return $c;
    }

    /**
     * Mentions the childcare of the opening hours as a list nested inside the day it belongs to.
     */
    public function withChildcareInNestedList(): self
    {
        $c = clone $this;
        $c->withChildcare = true;
        $c->childcareInNestedList = true;
        return $c;
    }

    /**
     * Warns that the listed hours do not hold during the adjusted days, which are not
     * listed themselves.
     */
    public function withAdjustedHoursNotice(): self
    {
        $c = clone $this;
        $c->withAdjustedHoursNotice = true;
        return $c;
    }

    /**
     * Lists every day of the week, marking the days without opening hours as closed.
     */
    public function withEveryDayOfTheWeek(): self
    {
        $c = clone $this;
        $c->withEveryDayOfTheWeek = true;
        return $c;
    }

    /**
     * Introduces the list with the 'open at' caption.
     */
    public function withHeading(): self
    {
        $c = clone $this;
        $c->withHeading = true;
        return $c;
    }

    public function toString(): string
    {
        // Without opening hours there is no week scheme to show. Saying nothing beats
        // an empty list or a week of closed days, which would claim the place is never
        // open while empty opening hours mean the opposite.
        if ($this->openingHours->isEmpty()) {
            return '';
        }

        // When every day has the same childcare it is summarized in a single list item
        // instead of being repeated on every day.
        $sharedChildcare = $this->withChildcare ? $this->openingHours->sharedChildcare() : null;

        // The childcare of a day is mentioned once after its last timespan, so it is
        // collected while the timespans are rendered.
        $childcarePerDay = [];

        $formattedDays = [];
        foreach ($this->openingHours as $openingHour) {
            $daysOfWeek = $openingHour->getDaysOfWeek();
            $opens = OpeningHourFormatter::format($openingHour->getOpens());
            $closes = OpeningHourFormatter::format($openingHour->getCloses());

            foreach ($daysOfWeek as $dayOfWeek) {
                $childcarePerDay[$dayOfWeek] = $this->childcareOfDay($dayOfWeek, $sharedChildcare);

                // Only when the timespans of this day have a different childcare it is
                // repeated after every single one of them.
                $childcare = $sharedChildcare === null && $childcarePerDay[$dayOfWeek] === null
                    ? $this->generateChildcare($openingHour->getChildcare())
                    : '';

                if (!isset($formattedDays[$dayOfWeek])) {
                    $formattedDays[$dayOfWeek] = $this->openDay($dayOfWeek, $daysOfWeek)
                        . $this->generateTimespan($opens, $closes)
                        . $childcare;
                } else {
                    $formattedDays[$dayOfWeek] .= $this->generateTimespan($opens, $closes, true) . $childcare;
                }
            }
        }

        foreach ($childcarePerDay as $dayOfWeek => $childcareOfDay) {
            $formattedDays[$dayOfWeek] .= $this->generateChildcare($childcareOfDay);
        }

        $output = $this->withHeading
            ? '<p class="cf-openinghours">' . ucfirst($this->translator->translate('open_at')) . ':</p>'
            : '';
        $output .= '<ul class="list-unstyled">';

        foreach ($this->sortedDays($formattedDays) as $formattedDay) {
            $output .= $formattedDay . '</li>';
        }

        if ($sharedChildcare !== null) {
            $output .= '<li class="cf-childcare">'
                . ChildcareFormatter::forChildcare($sharedChildcare, $this->translator)
                    ->forEveryDay()
                    ->capitalize()
                    ->toString()
                . '</li>';
        }

        if ($this->withAdjustedHoursNotice) {
            $output .= '<li class="cf-adjusted-days">'
                . $this->translator->translate('adjusted_hours_notice')
                . '</li>';
        }

        return $output . '</ul>';
    }

    private function childcareOfDay(string $dayOfWeek, ?Childcare $sharedChildcare): ?Childcare
    {
        if (!$this->withChildcare || $sharedChildcare !== null) {
            return null;
        }

        return $this->openingHours->onDayOfWeek($dayOfWeek)->sharedChildcare();
    }

    /**
     * Opens the list item of a day that has opening hours.
     *
     * @param string[] $daysOfWeek the days that share the timespans of this day
     */
    private function openDay(string $dayOfWeek, array $daysOfWeek): string
    {
        $firstOpens = OpeningHourFormatter::format($this->openingHours->earliestTimeOn($daysOfWeek));
        $lastCloses = OpeningHourFormatter::format($this->openingHours->latestTimeOn($daysOfWeek));
        $daySpanShort = ucfirst($this->formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($dayOfWeek)));

        return "<meta itemprop=\"openingHours\" datetime=\"$daySpanShort $firstOpens-$lastCloses\"> "
            . '</meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">' . $this->translateDayOfWeek($dayOfWeek) . '</span> ';
    }

    /**
     * Renders a single 'from ... till ...', prefixed with 'and' when the day already has one.
     */
    private function generateTimespan(string $opens, string $closes, bool $afterAnotherTimespan = false): string
    {
        $and = $afterAnotherTimespan ? $this->translator->translate('and') . ' ' : '';

        return "<span itemprop=\"opens\" content=\"$opens\" class=\"cf-from cf-meta\">"
            . $and . $this->translator->translate('from_hour') . '</span> '
            . "<span class=\"cf-time\">$opens</span> "
            . "<span itemprop=\"closes\" content=\"$closes\" class=\"cf-to cf-meta\">"
            . $this->translator->translate('till_hour') . '</span> '
            . "<span class=\"cf-time\">$closes</span>";
    }

    private function generateChildcare(?Childcare $childcare): string
    {
        if ($childcare === null) {
            return '';
        }

        $childcareText = ChildcareFormatter::forChildcare($childcare, $this->translator)
            ->withBraces()
            ->capitalize()
            ->toString();

        if ($this->childcareInNestedList) {
            return ' <ul class="list-unstyled">'
                . '<li class="cf-childcare">' . $childcareText . '</li>'
                . '</ul>';
        }

        return ' <span class="cf-childcare">' . $childcareText . '</span>';
    }

    /**
     * @param string[] $formattedDays the rendered days, keyed by day of the week
     * @return string[]
     */
    private function sortedDays(array $formattedDays): array
    {
        if (!$this->withEveryDayOfTheWeek) {
            return $formattedDays;
        }

        $sortedDays = [];
        foreach (OpeningHour::ALLOWED_DAYS as $dayOfWeek) {
            $sortedDays[$dayOfWeek] = $formattedDays[$dayOfWeek] ?? $this->closedDay($dayOfWeek);
        }

        return $sortedDays;
    }

    private function closedDay(string $dayOfWeek): string
    {
        $translatedDay = $this->translateDayOfWeek($dayOfWeek);

        return "<meta itemprop=\"openingHours\" datetime=\"$translatedDay\"> "
            . '</meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . "<span class=\"cf-days\">$translatedDay</span> "
            . '<span itemprop="closed" content="closed" class="cf-closed cf-meta">'
            . $this->translator->translate('closed') . '</span> ';
    }

    private function translateDayOfWeek(string $dayOfWeek): string
    {
        return ucfirst($this->formatter->formatAsDayOfWeek(new DateTimeImmutable($dayOfWeek)));
    }
}
