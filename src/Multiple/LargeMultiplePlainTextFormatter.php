<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\LargeSinglePlainTextFormatter;

final class LargeMultiplePlainTextFormatter implements MultipleFormatterInterface
{
    /**
     * @var string $langCode
     */
    private $langCode;

    /**
     * @var bool $hidePast
     */
    private $hidePast;

    public function __construct(string $langCode, bool $hidePastDates)
    {
        $this->langCode = $langCode;
        $this->hidePast = $hidePastDates;
    }

    public function format(Event $event): string
    {
        $subEvents = $event->getSubEvents();
        $subEventSummaries = [];

        foreach ($subEvents as $key => $subEvent) {
            $formatter = new LargeSinglePlainTextFormatter($this->langCode);

            if (!$this->hidePast || DateComparison::inTheFuture($subEvent->getEndDate())) {
                $subEventSummaries[] = $formatter->format($subEvent);
            }
        }

        return implode(PHP_EOL, $subEventSummaries);
    }
}
