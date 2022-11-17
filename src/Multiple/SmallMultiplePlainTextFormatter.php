<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\RelativeDatePlainTextFormatter;
use CultuurNet\CalendarSummaryV3\Translator;

final class SmallMultiplePlainTextFormatter implements MultipleFormatterInterface
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
            $relativeDate = $this->getRelativeDate($startDate, $this->translator, $this->formatter);
            if (isset($relativeDate)) {
                return $relativeDate;
            }

            $plainTextSummaryBuilder = PlainTextSummaryBuilder::start($this->translator)
                ->append($this->formatter->formatAsAbbreviatedDayOfWeek($startDate))
                ->append($this->formatter->formatAsDayNumber($startDate))
                ->append($this->formatter->formatAsAbbreviatedMonthName($startDate));
            if (!DateComparison::isCurrentYear($startDate)) {
                $plainTextSummaryBuilder = $plainTextSummaryBuilder->append($this->formatter->formatAsYear($startDate));
            }

            return $plainTextSummaryBuilder->appendAvailability($offer->getStatus(), $offer->getBookingAvailability())
                ->toString();
        }

        $plainTextSummaryBuilder = PlainTextSummaryBuilder::start($this->translator)
            ->append($this->formatter->formatAsDayNumber($startDate))
            ->append($this->formatter->formatAsAbbreviatedMonthName($startDate));
        if (!DateComparison::isCurrentYear($startDate)) {
            $plainTextSummaryBuilder = $plainTextSummaryBuilder->append($this->formatter->formatAsYear($startDate));
        }
        $plainTextSummaryBuilder = $plainTextSummaryBuilder->append('-')
            ->append($this->formatter->formatAsDayNumber($endDate))
            ->append($this->formatter->formatAsAbbreviatedMonthName($endDate));
        if (!DateComparison::isCurrentYear($endDate)) {
            $plainTextSummaryBuilder = $plainTextSummaryBuilder->append($this->formatter->formatAsYear($endDate));
        }

        return $plainTextSummaryBuilder
            ->appendAvailability($offer->getStatus(), $offer->getBookingAvailability())
            ->toString();
    }
}
