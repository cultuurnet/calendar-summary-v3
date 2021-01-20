<?php declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use DateTime;
use DateTimeZone;

final class ExtraSmallMultipleInformation
{
    /**
     * @var DateTime
     */
    private $dateFrom;

    /**
     * @var DateTime
     */
    private $dateTo;

    public function __construct(Event $event)
    {
        $this->dateFrom = $event->getStartDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $this->dateTo = $event->getEndDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
    }

    public function getFrom(): string
    {
        return $this->dateFrom->format('j/n/y');
    }

    public function getTo(): string
    {
        return $this->dateTo->format('j/n/y');
    }
}
