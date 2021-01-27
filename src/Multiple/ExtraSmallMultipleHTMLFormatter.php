<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use DateTimeZone;

final class ExtraSmallMultipleHTMLFormatter implements MultipleFormatterInterface
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
        $this->trans = new Translator($langCode);
    }

    public function format(Event $event): string
    {
        $dateFrom = $event->getStartDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $dateTo = $event->getEndDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));

        if (DateComparison::onSameDay($dateFrom, $dateTo)) {
            return '<span class="cf-date">' . $this->formatter->formatAsShortDate($dateFrom) . '</span>';
        }

        return '<span class="cf-from cf-meta">' . ucfirst($this->trans->getTranslations()->t('from')) . '</span> ' .
            '<span class="cf-date">' . $this->formatter->formatAsShortDate($dateFrom) . '</span> ' .
            '<span class="cf-to cf-meta">' . $this->trans->getTranslations()->t('till') . '</span> '.
            '<span class="cf-date">' . $this->formatter->formatAsShortDate($dateTo) . '</span>';
    }
}
