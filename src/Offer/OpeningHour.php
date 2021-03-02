<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use InvalidArgumentException;

final class OpeningHour
{
    private const ALLOWED_DAYS = [
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
     * @param string[] $daysOfWeek
     */
    public function __construct(
        array $daysOfWeek,
        string $opens,
        string $closes
    ) {
        foreach ($daysOfWeek as $day) {
            if (!in_array($day, self::ALLOWED_DAYS)) {
                throw new InvalidArgumentException('Invalid day: ' . $day);
            }
        }
        $this->daysOfWeek = $daysOfWeek;
        $this->opens = $opens;
        $this->closes = $closes;
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
}
