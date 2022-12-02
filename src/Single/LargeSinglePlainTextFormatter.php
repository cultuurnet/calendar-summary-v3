<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeInterface;

final class LargeSinglePlainTextFormatter implements SingleFormatterInterface
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

    public function format(Offer $offer): string
    {
        $startDate = $offer->getStartDate();
        $endDate = $offer->getEndDate();

        if (DateComparison::onSameDay($startDate, $endDate)) {
            $output = $this->formatSameDay($startDate, $endDate);
        } else {
            $output = $this->formatMoreDays($startDate, $endDate);
        }

        return PlainTextSummaryBuilder::start($this->translator)
            ->append($output)
            ->appendAvailability($offer->getStatus(), $offer->getBookingAvailability())
            ->toString();
    }

    private function formatSameDay(DateTimeInterface $startDate, DateTimeInterface $endDate): string
    {
        $formattedStartDate = $this->formatter->formatAsFullDate($startDate);
        $formattedStartDayOfWeek = $this->formatter->formatAsDayOfWeek($startDate);
        $formattedStartTime = $this->formatter->formatAsTime($startDate);
        $formattedEndTime = $this->formatter->formatAsTime($endDate);

        $summaryBuilder = PlainTextSummaryBuilder::start($this->translator)
            ->append($formattedStartDayOfWeek)
            ->append($formattedStartDate);

        if ($formattedStartTime === '00:00' && $formattedEndTime === '23:59') {
            return $summaryBuilder->toString();
        }

        if ($formattedStartTime === $formattedEndTime) {
            return $summaryBuilder
                ->at($formattedStartTime)
                ->toString();
        }

        return $summaryBuilder
            ->fromHour($formattedStartTime)
            ->tillHour($formattedEndTime)
            ->toString();
    }

    private function formatMoreDays(DateTimeInterface $startDate, DateTimeInterface $endDate): string
    {
        $formattedStartDate = $this->formatter->formatAsFullDate($startDate);
        $formattedStartDayOfWeek = $this->formatter->formatAsDayOfWeek($startDate);
        $formattedStartTime = $this->formatter->formatAsTime($startDate);

        $formattedEndDate = $this->formatter->formatAsFullDate($endDate);
        $formattedEndDayOfWeek = $this->formatter->formatAsDayOfWeek($endDate);
        $formattedEndTime = $this->formatter->formatAsTime($endDate);

        return PlainTextSummaryBuilder::start($this->translator)
            ->from($formattedStartDayOfWeek, $formattedStartDate)
            ->at($formattedStartTime)
            ->tillLarge($formattedEndDayOfWeek, $formattedEndDate)
            ->at($formattedEndTime)
            ->toString();
    }
}
