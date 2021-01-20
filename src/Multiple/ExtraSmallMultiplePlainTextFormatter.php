<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;

class ExtraSmallMultiplePlainTextFormatter extends ExtraSmallMultipleFormatter implements MultipleFormatterInterface
{
    public function format(Event $event): string
    {
        $info = new ExtraSmallMultipleInformation($event);

        if ($info->getFrom() === $info->getTo()) {
            return $info->getFrom();
        }

        return ucfirst($this->trans->getTranslations()->t('from')) . ' ' .
            $info->getFrom() . ' ' .
            $this->trans->getTranslations()->t('till') . ' '.
            $info->getTo();
    }
}
