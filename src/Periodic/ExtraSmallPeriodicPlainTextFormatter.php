<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;

final class ExtraSmallPeriodicPlainTextFormatter implements PeriodicFormatterInterface
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
        $startDate = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $startDate->setTime(0, 0, 1);

        if (DateComparison::inTheFuture($startDate)) {
            $plainTextSummaryBuilder = PlainTextSummaryBuilder::start($this->translator)
                ->fromPeriod(
                    $this->formatter->formatAsDayNumber($startDate),
                    $this->formatter->formatAsAbbreviatedMonthName($startDate)
                );
            if (!DateComparison::isCurrentYear($startDate)) {
                $plainTextSummaryBuilder = $plainTextSummaryBuilder->append($this->formatter->formatAsYear($startDate));
            }
            return $plainTextSummaryBuilder->appendAvailability($offer->getStatus(), $offer->getBookingAvailability())
                ->toString();
        }

        $endDate = $offer->getEndDate();
        $plainTextSummaryBuilder = PlainTextSummaryBuilder::start($this->translator)
            ->till(
                $this->formatter->formatAsDayNumber($endDate),
                $this->formatter->formatAsAbbreviatedMonthName($endDate)
            );
        if (!DateComparison::isCurrentYear($endDate)) {
            $plainTextSummaryBuilder = $plainTextSummaryBuilder->append($this->formatter->formatAsYear($endDate));
        }
        return $plainTextSummaryBuilder->appendAvailability($offer->getStatus(), $offer->getBookingAvailability())
            ->toString();
    }
}
