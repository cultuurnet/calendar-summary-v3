<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use InvalidArgumentException;

final class Childcare
{
    /**
     * @var string|null
     */
    private $start;

    /**
     * @var string|null
     */
    private $end;

    /**
     * Either the start or the end can be missing, but not both: childcare only
     * before the opening hours has no end, childcare only after them has no start.
     */
    public function __construct(?string $start, ?string $end)
    {
        if ($start === null && $end === null) {
            throw new InvalidArgumentException('Childcare needs at least a start or an end');
        }

        $this->start = $start;
        $this->end = $end;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['start'] ?? null, $data['end'] ?? null);
    }

    /**
     * Returns the childcare that every opening hour has in common, or null when they
     * differ or when at least one opening hour has no childcare at all.
     *
     * @param OpeningHour[] $openingHours
     */
    public static function sharedByAll(array $openingHours): ?self
    {
        $shared = null;

        foreach ($openingHours as $openingHour) {
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

    public function getStart(): ?string
    {
        return $this->start;
    }

    public function getEnd(): ?string
    {
        return $this->end;
    }

    public function equals(self $other): bool
    {
        return $this->start === $other->start && $this->end === $other->end;
    }
}
