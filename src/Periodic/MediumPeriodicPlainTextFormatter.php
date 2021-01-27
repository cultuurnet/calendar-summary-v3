<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;

final class MediumPeriodicPlainTextFormatter implements PeriodicFormatterInterface
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
        $formattedStartDate = $this->formatter->formatAsFullDate($startDate);
        $formattedStartDayOfWeek = $this->formatter->formatAsDayOfWeek($startDate);

        $endDate = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $formattedEndDate = $this->formatter->formatAsFullDate($endDate);

        if ($formattedStartDate === $formattedEndDate) {
            return PlainTextSummaryBuilder::singleLine($formattedStartDayOfWeek, $formattedStartDate);
        }

        return PlainTextSummaryBuilder::start($this->translator)
            ->from($formattedStartDate)
            ->till($formattedEndDate)
            ->toString();
    }
}
