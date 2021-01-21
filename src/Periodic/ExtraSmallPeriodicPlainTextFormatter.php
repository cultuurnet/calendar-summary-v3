<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use \DateTime;
use \DateTimeInterface;

final class ExtraSmallPeriodicPlainTextFormatter implements PeriodicFormatterInterface
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
        $startDate = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $startDate->setTime(0, 0, 1);

        if (DateComparison::inTheFuture($startDate)) {
            return $this->formatNotStarted($startDate);
        }

        $endDate = $offer->getEndDate();
        return $this->formatStarted($endDate);
    }

    private function formatStarted(DateTimeInterface $endDate): string
    {
        return (new PlainTextSummaryBuilder($this->trans))
            ->till($this->formatter->formatAsShortDate($endDate))
            ->toString();
    }

    private function formatNotStarted(DateTimeInterface $startDate): string
    {
        return (new PlainTextSummaryBuilder($this->trans))
            ->fromPeriod($this->formatter->formatAsShortDate($startDate))
            ->toString();
    }
}
