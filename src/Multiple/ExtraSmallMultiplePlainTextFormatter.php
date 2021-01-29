<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
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
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->formatter = new DateFormatter($translator->getLocale());
        $this->translator = $translator;
    }

    public function format(Event $event): string
    {
        $startDate = $event->getStartDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $endDate = $event->getEndDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));

        if (DateComparison::onSameDay($startDate, $endDate)) {
            return $this->formatter->formatAsShortDate($startDate);
        }

        return PlainTextSummaryBuilder::start($this->translator)
            ->from($this->formatter->formatAsShortDate($startDate))
            ->till($this->formatter->formatAsShortDate($endDate))
            ->appendStatus($event->getStatus())
            ->toString();
    }
}
