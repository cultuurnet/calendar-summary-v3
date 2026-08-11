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
