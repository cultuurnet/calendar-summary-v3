<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use DateTimeZone;

final class ExtraSmallMultiplePlainTextFormatter implements MultipleFormatterInterface
{
    /**
     * @var DateFormatter
     */
    private $formatter;

    /**
     * @var Translator
     */
    private $trans;

    public function __construct(string $langCode)
    {
        $this->formatter = new DateFormatter($langCode);
        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

    public function format(Event $event): string
    {
        $dateFrom = $event->getStartDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $dateTo = $event->getEndDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));

        if ($dateFrom == $dateTo) {
            return $this->formatter->formatAsShortDate($dateFrom);
        }

        return ucfirst($this->trans->getTranslations()->t('from')) . ' ' .
            $this->formatter->formatAsShortDate($dateFrom) . ' ' .
            $this->trans->getTranslations()->t('till') . ' '.
            $this->formatter->formatAsShortDate($dateTo);
    }
}
