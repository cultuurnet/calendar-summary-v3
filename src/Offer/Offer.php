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
     * @var Status
     */
    private $status;

    /**
     * @var BookingAvailability
     */
    private $bookingAvailability;

    /**
     * @var DateTimeImmutable|null
     */
    private $startDate;

    /**
     * @var DateTimeImmutable|null
     */
    private $endDate;

    /**
     * @var CalendarType|null
     */
    private $calendarType;

    /**
     * @var Offer[]
     */
    private $subEvents = [];

    /**
     * @var OpeningHour[]
     */
    private $openingHours = [];

    public function __construct(
        OfferType $offerType,
        Status $status,
        BookingAvailability $bookingAvailability,
        ?DateTimeImmutable $startDate = null,
        ?DateTimeImmutable $endDate = null,
        ?CalendarType $calendarType = null
    ) {
        $this->offerType = $offerType;
        $this->status = $status;
        $this->bookingAvailability = $bookingAvailability;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->calendarType = $calendarType;
    }

    public static function fromJsonLd(string $json): self
    {
        $data = json_decode($json, true);

        $offer = new self(
            OfferType::fromContext(strtolower($data['@context'])),
            Status::fromArray($data['status']),
            BookingAvailability::fromArray($data['bookingAvailability']),
            isset($data['startDate']) ? new DateTimeImmutable($data['startDate']) : null,
            isset($data['endDate']) ? new DateTimeImmutable($data['endDate']) : null,
            new CalendarType($data['calendarType'])
        );

        if (isset($data['subEvent'])) {
            $offer = $offer->withSubEvents(self::parseSubEvents($data['subEvent']));
        }

        if (isset($data['openingHours'])) {
            $offer = $offer->withOpeningHours(self::parseOpeningHours($data['openingHours']));
        }

        return $offer;
    }

    /**
     * @return Offer[]
     */
    private static function parseSubEvents(array $data): array
    {
        $subEvents = [];
        foreach ($data as $subEventData) {
            $subEvents[] = new self(
                OfferType::event(),
                Status::fromArray($subEventData['status']),
                BookingAvailability::fromArray($subEventData['bookingAvailability']),
                new DateTimeImmutable($subEventData['startDate']),
                new DateTimeImmutable($subEventData['endDate'])
            );
        }

        return $subEvents;
    }

    /**
     * @return OpeningHour[]
     */
    private static function parseOpeningHours(array $data): array
    {
        $openingHours = [];
        foreach ($data as $openingHourData) {
            $openingHours[] = new OpeningHour(
                $openingHourData['dayOfWeek'],
                $openingHourData['opens'],
                $openingHourData['closes']
            );
        }

        return $openingHours;
    }

    /**
     * @param Offer[] $subEvents
     */
    public function withSubEvents(array $subEvents): self
    {
        $clone = clone $this;
        $clone->subEvents = $subEvents;

        return $clone;
    }

    /**
     * @param OpeningHour[] $openingHours
     */
    public function withOpeningHours(array $openingHours): self
    {
        $clone = clone $this;
        $clone->openingHours = $openingHours;

        return $clone;
    }

    public function withAvailability(Status $status, BookingAvailability $bookingAvailability): self
    {
        $clone = clone $this;

        $clone->status = $status;
        $clone->bookingAvailability = $bookingAvailability;

        return $clone;
    }

    public function getCalendarType(): ?CalendarType
    {
        return $this->calendarType;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getBookingAvailability(): BookingAvailability
    {
        return $this->bookingAvailability;
    }

    public function isAvailable(): bool
    {
        return $this->status->getType() === 'Available' && $this->bookingAvailability->getType() === 'Available';
    }

    public function getStartDate(): ?DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): ?DateTimeImmutable
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

    /**
     * @return Offer[]
     */
    public function getSubEvents(): array
    {
        return $this->subEvents;
    }

    /**
     * @return OpeningHour[]
     */
    public function getOpeningHours(): array
    {
        return $this->openingHours;
    }
}
