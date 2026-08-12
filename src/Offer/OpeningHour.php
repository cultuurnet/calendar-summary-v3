<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use InvalidArgumentException;

final class OpeningHour
{
    public const ALLOWED_DAYS = [
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];

    /**
     * @var string[]
     */
    private $daysOfWeek;

    /**
     * @var string
     */
    private $opens;

    /**
     * @var string
     */
    private $closes;

    /**
     * @var Childcare|null
     */
    private $childcare;

    /**
     * @param string[] $daysOfWeek
     */
    public function __construct(
        array $daysOfWeek,
        string $opens,
        string $closes,
        ?Childcare $childcare = null
    ) {
        foreach ($daysOfWeek as $day) {
            if (!in_array($day, self::ALLOWED_DAYS)) {
                throw new InvalidArgumentException('Invalid day: ' . $day);
            }
        }
        $this->daysOfWeek = $daysOfWeek;
        $this->opens = $opens;
        $this->closes = $closes;
        $this->childcare = $childcare;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['dayOfWeek'],
            $data['opens'],
            $data['closes'],
            self::parseChildcare($data)
        );
    }

    private static function parseChildcare(array $data): ?Childcare
    {
        // Childcare needs at least a start or an end, anything else counts as no childcare.
        if (!isset($data['childcare']['start']) && !isset($data['childcare']['end'])) {
            return null;
        }

        return Childcare::fromArray($data['childcare']);
    }

    /**
     * @return string[]
     */
    public function getDaysOfWeek(): array
    {
        return $this->daysOfWeek;
    }

    public function getOpens(): string
    {
        return $this->opens;
    }

    public function getCloses(): string
    {
        return $this->closes;
    }

    public function getChildcare(): ?Childcare
    {
        return $this->childcare;
    }
}
