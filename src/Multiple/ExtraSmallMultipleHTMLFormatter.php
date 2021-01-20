<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;

class ExtraSmallMultipleHTMLFormatter extends ExtraSmallMultipleFormatter implements MultipleFormatterInterface
{
    public function format(Event $event): string
    {
        $info = new ExtraSmallMultipleInformation($event);

        if ($info->getFrom() === $info->getTo()) {
            return '<span class="cf-date">' . $info->getFrom() . '</span>';
        }

        return '<span class="cf-from cf-meta">' . ucfirst($this->trans->getTranslations()->t('from')) . '</span> ' .
            '<span class="cf-date">' . $info->getFrom() . '</span> ' .
            '<span class="cf-to cf-meta">' . $this->trans->getTranslations()->t('till') . '</span> '.
            '<span class="cf-date">' . $info->getTo() . '</span>';
    }
}
