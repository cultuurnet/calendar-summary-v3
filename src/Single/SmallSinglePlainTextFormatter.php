<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\RelativeDatePlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeInterface;

final class SmallSinglePlainTextFormatter implements SingleFormatterInterface
{
    use RelativeDatePlainTextFormatter;
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
            $output = $this->formatSameDay($startDate);
        } else {
            $output = $this->formatMoreDays($startDate, $endDate);
        }

        return PlainTextSummaryBuilder::start($this->translator)
            ->append($output)
            ->appendAvailability($offer->getStatus(), $offer->getBookingAvailability())
            ->toString();
    }

    private function formatSameDay(DateTimeInterface $date): string
    {
        $relativeDate = $this->getRelativeDate($date, $this->translator, $this->formatter);
        if ($relativeDate !== null) {
            return $relativeDate;
        }

        $dayNumber = $this->formatter->formatAsDayNumber($date);
        $monthName = $this->formatter->formatAsAbbreviatedMonthName($date);
        if (DateComparison::isCurrentYear($date)) {
            return PlainTextSummaryBuilder::singleLine($dayNumber, $monthName);
        }
        $year = $this->formatter->formatAsYear($date);
        return PlainTextSummaryBuilder::singleLine($dayNumber, $monthName, $year);
    }

    private function formatMoreDays(DateTimeInterface $startDate, DateTimeInterface $endDate): string
    {
        $startDay = [];
        $startDay[] = $this->formatter->formatAsDayNumber($startDate);
        $startDay[] = $this->formatter->formatAsAbbreviatedMonthName($startDate);

        $endDay = [];
        $endDay[] = $this->formatter->formatAsDayNumber($endDate);
        $endDay[] = $this->formatter->formatAsAbbreviatedMonthName($endDate);

        return PlainTextSummaryBuilder::start($this->translator)
            ->fromTill($startDay, $endDay)
            ->toString();
    }
}
