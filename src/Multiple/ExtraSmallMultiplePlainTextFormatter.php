<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;
use DateTimeZone;

class ExtraSmallMultiplePlainTextFormatter extends ExtraSmallMultipleFormatter implements MultipleFormatterInterface
{
    public function format(Event $event): string
    {
        $dateFrom = $event->getStartDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $dateTo = $event->getEndDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));

        if ($dateFrom == $dateTo) {
            return $dateFrom->format('j/n/y');
        }

        return ucfirst($this->trans->getTranslations()->t('from')) . ' ' .
            $dateFrom->format('j/n/y') . ' ' .
            $this->trans->getTranslations()->t('till') . ' '.
            $dateTo->format('j/n/y');
    }
}
