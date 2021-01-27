<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTimeInterface;

final class SmallSinglePlainTextFormatter implements SingleFormatterInterface
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
            $output = $this->formatSameDay($startDate);
        } else {
            $output = $this->formatMoreDays($startDate, $endDate);
        }

        return $output;
    }

    private function formatSameDay(DateTimeInterface $date): string
    {
        $dayNumber = $this->formatter->formatAsDayNumber($date);
        $monthName = $this->formatter->formatAsAbbreviatedMonthName($date);
        return PlainTextSummaryBuilder::singleLine($dayNumber, $monthName);
    }

    private function formatMoreDays(DateTimeInterface $startDate, DateTimeInterface $endDate): string
    {
        $startDayNumber = $this->formatter->formatAsDayNumber($startDate);
        $startMonthName = $this->formatter->formatAsAbbreviatedMonthName($startDate);

        $endDayNumber = $this->formatter->formatAsDayNumber($endDate);
        $endMonthName = $this->formatter->formatAsAbbreviatedMonthName($endDate);

        return PlainTextSummaryBuilder::start($this->translator)
            ->from($startDayNumber, $startMonthName)
            ->till($endDayNumber, $endMonthName)
            ->toString();
    }
}
