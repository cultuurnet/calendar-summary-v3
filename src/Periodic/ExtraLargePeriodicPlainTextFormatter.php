<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\PlainTextAdjustedDaysFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextClosedDaysFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\PlainTextWeekSchemeFormatter;
use CultuurNet\CalendarSummaryV3\Translator;

final class ExtraLargePeriodicPlainTextFormatter implements PeriodicFormatterInterface
{
    private DateFormatter $formatter;

    private Translator $translator;

    private PlainTextAdjustedDaysFormatter $adjustedDaysFormatter;

    private PlainTextClosedDaysFormatter $closedDaysFormatter;

    public function __construct(Translator $translator)
    {
        $this->formatter = new DateFormatter($translator->getLocale());
        $this->translator = $translator;
        $this->adjustedDaysFormatter = new PlainTextAdjustedDaysFormatter($translator);
        $this->closedDaysFormatter = new PlainTextClosedDaysFormatter($translator);
    }

    public function format(Offer $offer): string
    {
        $startDate = $offer->getStartDate();
        $endDate = $offer->getEndDate();

        // The week scheme can span multiple lines, so unlike the large format the
        // availability follows the dates instead of the opening hours.
        $summary = PlainTextSummaryBuilder::start($this->translator)
            ->from(
                $this->formatter->formatAsDayOfWeek($startDate),
                $this->formatter->formatAsFullDate($startDate)
            )
            ->to(
                $this->formatter->formatAsDayOfWeek($endDate),
                $this->formatter->formatAsFullDate($endDate)
            )
            ->appendAvailability($offer->getStatus(), $offer->getBookingAvailability());

        if (!$offer->getOpeningHours()->isEmpty()) {
            $summary = $summary
                ->startNewLine()
                ->append(
                    PlainTextWeekSchemeFormatter::forOpeningHours($offer->getOpeningHours(), $this->translator)
                        ->asSingleLine()
                        ->withChildcare()
                        ->toString()
                );
        }

        return $summary->toString() . $this->generatePeriods($offer);
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
