<?php

declare(strict_types=1);

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

        $summaryBuilder = PlainTextSummaryBuilder::start($this->translator);

        if ($formattedStartDate === $formattedEndDate) {
            return $summaryBuilder->append($formattedStartDayOfWeek)
                ->append($formattedStartDate)
                ->appendStatus($offer->getStatus())
                ->toString();
        }

        return $summaryBuilder
            ->from($formattedStartDate)
            ->till($formattedEndDate)
            ->appendStatus($offer->getStatus())
            ->toString();
    }
}
