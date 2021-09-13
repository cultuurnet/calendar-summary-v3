<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeZone;

final class ExtraSmallMultiplePlainTextFormatter implements MultipleFormatterInterface
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
        $startDate = $offer->getStartDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $endDate = $offer->getEndDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));

        if (DateComparison::onSameDay($startDate, $endDate)) {
            return PlainTextSummaryBuilder::start($this->translator)
                ->append($this->formatter->formatAsShortDate($startDate))
                ->appendAvailability($offer->getStatus(), $offer->getBookingAvailability())
                ->toString();
        }

        return PlainTextSummaryBuilder::start($this->translator)
            ->from($this->formatter->formatAsShortDate($startDate))
            ->till($this->formatter->formatAsShortDate($endDate))
            ->appendAvailability($offer->getStatus(), $offer->getBookingAvailability())
            ->toString();
    }
}
