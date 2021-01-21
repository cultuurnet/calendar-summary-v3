<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
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
    private $trans;

    public function __construct(string $langCode)
    {
        $this->formatter = new DateFormatter($langCode);

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
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

        return $output;
    }

    private function formatSameDay(DateTimeInterface $startDate, DateTimeInterface $endDate): string
    {
        $formattedStartDate = $this->formatter->formatAsFullDate($startDate);
        $formattedStartDayOfWeek = $this->formatter->formatAsDayOfWeek($startDate);
        $formattedStartTime = $this->formatter->formatAsTime($startDate);
        $formattedEndTime = $this->formatter->formatAsTime($endDate);

        $summaryBuilder = (new PlainTextSummaryBuilder($this->trans))
            ->add($formattedStartDayOfWeek)
            ->add($formattedStartDate);

        if ($formattedStartTime === '00:00' && $formattedEndTime === '23:59') {
            return $summaryBuilder->toString();
        }

        if ($formattedStartTime === $formattedEndTime) {
            return $summaryBuilder
                ->at($formattedStartTime)
                ->toString();
        }

        return $summaryBuilder
            ->from($formattedStartTime)
            ->till($formattedEndTime);
    }

    private function formatMoreDays(DateTimeInterface $startDate, DateTimeInterface $endDate): string
    {
        $formattedStartDate = $this->formatter->formatAsFullDate($startDate);
        $formattedStartDayOfWeek = $this->formatter->formatAsDayOfWeek($startDate);
        $formattedStartTime = $this->formatter->formatAsTime($startDate);

        $formattedEndDate = $this->formatter->formatAsFullDate($endDate);
        $formattedEndDayOfWeek = $this->formatter->formatAsDayOfWeek($endDate);
        $formattedEndTime = $this->formatter->formatAsTime($endDate);

        return (new PlainTextSummaryBuilder($this->trans))
            ->from($formattedStartDayOfWeek, $formattedStartDate)
            ->at($formattedStartTime)
            ->till($formattedEndDayOfWeek, $formattedEndDate)
            ->at($formattedEndTime);
    }
}
