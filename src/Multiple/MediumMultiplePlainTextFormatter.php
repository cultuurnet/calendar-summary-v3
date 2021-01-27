<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\MediumSinglePlainTextFormatter;

final class MediumMultiplePlainTextFormatter implements MultipleFormatterInterface
{
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var bool
     */
    private $hidePast;

    public function __construct(Translator $translator, bool $hidePastDates)
    {
        $this->translator = $translator;
        $this->hidePast = $hidePastDates;
    }

    public function format(Event $event): string
    {
        $subEvents = $event->getSubEvents();
        $subEventSummaries = [];

        foreach ($subEvents as $key => $subEvent) {
            $formatter = new MediumSinglePlainTextFormatter($this->translator);

            if (!$this->hidePast || DateComparison::inTheFuture($subEvent->getEndDate())) {
                $subEventSummaries[] = $formatter->format($subEvent);
            }
        }

        return implode(PHP_EOL, $subEventSummaries);
    }
}
