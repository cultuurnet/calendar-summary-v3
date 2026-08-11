<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use DateTimeImmutable;

final class AdjustedDay implements Period
{
    /**
     * @var DateTimeImmutable
     */
    private $startDate;

    /**
     * @var DateTimeImmutable
     */
    private $endDate;

    /**
     * @var OpeningHours
     */
    private $openingHours;

    /**
     * @var string[]
     */
    private $description;

    /**
     * @param OpeningHour[] $openingHours
     * @param string[] $description
     */
    public function __construct(
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        array $openingHours = [],
        array $description = []
    ) {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->openingHours = new OpeningHours($openingHours);
        $this->description = $description;
    }

    public static function fromArray(array $data): self
    {
        $openingHours = [];
        if (isset($data['openingHours'])) {
            foreach ($data['openingHours'] as $openingHourData) {
                $openingHours[] = OpeningHour::fromArray($openingHourData);
            }
        }

        return new self(
            new DateTimeImmutable($data['startDate']),
            new DateTimeImmutable($data['endDate']),
            $openingHours,
            $data['description'] ?? []
        );
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function getOpeningHours(): OpeningHours
    {
        return $this->openingHours;
    }

    public function getDescriptionForLanguage(string $languageCode): string
    {
        if (!isset($this->description[$languageCode])) {
            return '';
        }

        return $this->description[$languageCode];
    }
}
