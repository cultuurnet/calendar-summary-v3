<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Translator;
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

    public function format(Offer $offer): string
    {
        $subEvents = $offer->getSubEvents();
        $subEventSummaries = [];

        foreach ($subEvents as $key => $subEvent) {
            $formatter = new MediumSinglePlainTextFormatter($this->translator);

            if (!$this->hidePast || DateComparison::isInTheFuture($subEvent->getEndDate())) {
                $subEventSummaries[] = $formatter->format($subEvent);
            }
        }

        if (empty($subEventSummaries)) {
            return $this->translator->translate('event_concluded');
        }

        return implode(PHP_EOL, array_unique($subEventSummaries));
    }
}
