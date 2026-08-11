<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<int, OpeningHour>
 */
final class OpeningHours implements IteratorAggregate, Countable
{
    /**
     * @var OpeningHour[]
     */
    private $openingHours;

    /**
     * @param OpeningHour[] $openingHours
     */
    public function __construct(array $openingHours = [])
    {
        $this->openingHours = array_values($openingHours);
    }

    /**
     * Returns the same opening hours, but with a separate opening hour per day of the week.
     */
    public function splitPerDay(): self
    {
        $individualOpeningHours = [];
        foreach ($this->openingHours as $openingHour) {
            foreach ($openingHour->getDaysOfWeek() as $dayOfWeek) {
                $individualOpeningHours[] = new OpeningHour(
                    [$dayOfWeek],
                    $openingHour->getOpens(),
                    $openingHour->getCloses(),
                    $openingHour->getChildcare()
                );
            }
        }

        return new self($individualOpeningHours);
    }

    public function sortedByDayAndOpeningTime(): self
    {
        $sorted = $this->openingHours;

        usort($sorted, static function (OpeningHour $a, OpeningHour $b) {
            $weekdayA = array_search($a->getDaysOfWeek()[0], OpeningHour::ALLOWED_DAYS);
            $weekdayB = array_search($b->getDaysOfWeek()[0], OpeningHour::ALLOWED_DAYS);
            $fullHoursA = $weekdayA * 24 + (int) $a->getOpens();
            $fullHoursB = $weekdayB * 24 + (int) $b->getOpens();

            return $fullHoursA <=> $fullHoursB;
        });

        return new self($sorted);
    }

    /**
     * Returns the opening hours that apply on the given day of the week.
     */
    public function onDayOfWeek(string $dayOfWeek): self
    {
        return new self(
            array_filter(
                $this->openingHours,
                static function (OpeningHour $openingHour) use ($dayOfWeek): bool {
                    return in_array($dayOfWeek, $openingHour->getDaysOfWeek(), true);
                }
            )
        );
    }

    /**
     * Returns the childcare that every opening hour has in common, or null when they
     * differ or when at least one opening hour has no childcare at all.
     */
    public function sharedChildcare(): ?Childcare
    {
        $shared = null;

        foreach ($this->openingHours as $openingHour) {
            $childcare = $openingHour->getChildcare();

            if ($childcare === null) {
                return null;
            }

            if ($shared === null) {
                $shared = $childcare;
                continue;
            }

            if (!$shared->equals($childcare)) {
                return null;
            }
        }

        return $shared;
    }

    /**
     * Retrieve the earliest time for the specified daysOfWeek.
     *
     * @param string[] $daysOfWeek
     */
    public function earliestTimeOn(array $daysOfWeek): string
    {
        $earliest = '';
        foreach ($this->withExactlyTheseDaysOfWeek($daysOfWeek) as $openingHour) {
            if ($earliest === '' || $openingHour->getOpens() < $earliest) {
                $earliest = $openingHour->getOpens();
            }
        }

        return $earliest;
    }

    /**
     * Retrieve the latest time for the specified daysOfWeek.
     *
     * @param string[] $daysOfWeek
     */
    public function latestTimeOn(array $daysOfWeek): string
    {
        $latest = '';
        foreach ($this->withExactlyTheseDaysOfWeek($daysOfWeek) as $openingHour) {
            if ($latest === '' || $openingHour->getCloses() > $latest) {
                $latest = $openingHour->getCloses();
            }
        }

        return $latest;
    }

    public function isEmpty(): bool
    {
        return $this->openingHours === [];
    }

    /**
     * @return OpeningHour[]
     */
    public function toArray(): array
    {
        return $this->openingHours;
    }

    public function count(): int
    {
        return count($this->openingHours);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->openingHours);
    }

    /**
     * @param string[] $daysOfWeek
     * @return OpeningHour[]
     */
    private function withExactlyTheseDaysOfWeek(array $daysOfWeek): array
    {
        return array_filter(
            $this->openingHours,
            static function (OpeningHour $openingHour) use ($daysOfWeek): bool {
                return $daysOfWeek === $openingHour->getDaysOfWeek();
            }
        );
    }
}
