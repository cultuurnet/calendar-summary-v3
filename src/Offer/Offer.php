<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

final class Offer
{
    /**
     * @var CalendarType
     */
    private $calendarType;

    public function __construct(CalendarType $calendarType)
    {
        $this->calendarType = $calendarType;
    }

    public static function fromJsonLd(string $json): self
    {
        $data = json_decode($json, true);

        return new self(
            new CalendarType($data['calendarType'])
        );
    }

    public function getCalendarType(): CalendarType
    {
        return $this->calendarType;
    }
}
