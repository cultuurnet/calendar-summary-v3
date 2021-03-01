<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTimeInterface;

final class MediumSinglePlainTextFormatter implements SingleFormatterInterface
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

        return PlainTextSummaryBuilder::start($this->translator)
            ->append($output)
            ->appendStatus($offer->getStatus())
            ->toString();
    }

    private function formatSameDay(DateTimeInterface $date): string
    {
        $formattedDate = $this->formatter->formatAsFullDate($date);
        $formattedWeekDay = $this->formatter->formatAsDayOfWeek($date);
        return PlainTextSummaryBuilder::singleLine($formattedWeekDay, $formattedDate);
    }

    private function formatMoreDays(DateTimeInterface $startDate, DateTimeInterface $endDate): string
    {
        $formattedStartDate = $this->formatter->formatAsFullDate($startDate);
        $formattedStartDayOfWeek = $this->formatter->formatAsDayOfWeek($startDate);

        $formattedEndDate = $this->formatter->formatAsFullDate($endDate);
        $formattedEndDayOfWeek = $this->formatter->formatAsDayOfWeek($endDate);

        return PlainTextSummaryBuilder::start($this->translator)
            ->from($formattedStartDayOfWeek, $formattedStartDate)
            ->till($formattedEndDayOfWeek, $formattedEndDate)
            ->toString();
    }
}
