<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use DateTimeImmutable;

final class Offer
{
    /**
     * @var OfferType
     */
    private $offerType;

    /**
     * @var CalendarType
     */
    private $calendarType;

    /**
     * @var DateTimeImmutable
     */
    private $startDate;

    /**
     * @var DateTimeImmutable
     */
    private $endDate;

    public function __construct(
        OfferType $offerType,
        CalendarType $calendarType,
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate
    ) {
        $this->offerType = $offerType;
        $this->calendarType = $calendarType;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public static function fromJsonLd(string $json): self
    {
        $data = json_decode($json, true);

        return new self(
            new OfferType(mb_strtolower($data['@type'])),
            new CalendarType($data['calendarType']),
            new DateTimeImmutable($data['startDate']),
            new DateTimeImmutable($data['endDate'])
        );
    }

    public function getCalendarType(): CalendarType
    {
        return $this->calendarType;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function isEvent(): bool
    {
        return $this->offerType->equals(OfferType::event());
    }

    public function isPlace(): bool
    {
        return $this->offerType->equals(OfferType::place());
    }
}
