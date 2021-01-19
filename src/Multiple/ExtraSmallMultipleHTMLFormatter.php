<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use DateTimeZone;

final class ExtraSmallMultipleHTMLFormatter implements MultipleFormatterInterface
{
    /**
     * @var Translator
     */
    private $trans;

    public function __construct(string $langCode)
    {
        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

    public function format(Event $event): string
    {
        $dateFrom = $event->getStartDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $dateTo = $event->getEndDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));

        if ($dateFrom == $dateTo) {
            return '<span class="cf-date">' . $dateFrom->format('j/n/y') . '</span>';
        }

        return '<span class="cf-from cf-meta">' . ucfirst($this->trans->getTranslations()->t('from')) . '</span> ' .
            '<span class="cf-date">' . $dateFrom->format('j/n/y') . '</span> ' .
            '<span class="cf-to cf-meta">' . $this->trans->getTranslations()->t('till') . '</span> '.
            '<span class="cf-date">' . $dateTo->format('j/n/y') . '</span>';
    }
}
