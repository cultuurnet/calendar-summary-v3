<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\PlainTextAdjustedDaysFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextClosedDaysFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\PlainTextWeekSchemeFormatter;
use CultuurNet\CalendarSummaryV3\Translator;

final class ExtraLargePermanentPlainTextFormatter implements PermanentFormatterInterface
{
    private Translator $translator;

    private PlainTextAdjustedDaysFormatter $adjustedDaysFormatter;

    private PlainTextClosedDaysFormatter $closedDaysFormatter;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->adjustedDaysFormatter = new PlainTextAdjustedDaysFormatter($translator);
        $this->closedDaysFormatter = new PlainTextClosedDaysFormatter($translator);
    }

    public function format(Offer $offer): string
    {
        if ($offer->getStatus()->getType() === 'Unavailable') {
            return ucfirst($this->translator->translate('cancelled'));
        }

        if ($offer->getStatus()->getType() === 'TemporarilyUnavailable') {
            return ucfirst($this->translator->translate('postponed'));
        }

        if (!$offer->getOpeningHours()->isEmpty()) {
            $output = PlainTextWeekSchemeFormatter::forOpeningHours($offer->getOpeningHours(), $this->translator)
                ->withChildcare()
                ->toString();
        } else {
            $output = PlainTextSummaryBuilder::start($this->translator)
                ->alwaysOpen()
                ->toString();
        }

        return $output . $this->generatePeriods($offer) . PHP_EOL;
    }

    /**
     * The adjusted and closed days get some visual space, both from the opening
     * hours above them and from each other.
     */
    private function generatePeriods(Offer $offer): string
    {
        $periods = array_filter([
            $this->adjustedDaysFormatter->format($offer->getAdjustedDays()),
            $this->closedDaysFormatter->format($offer->getClosedDays()),
        ]);

        if (!$periods) {
            return '';
        }

        $separator = PHP_EOL . PHP_EOL;

        return $separator . implode($separator, $periods);
    }
}
