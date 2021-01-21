<?php

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
    private $trans;

    public function __construct(string $langCode)
    {
        $this->formatter = new DateFormatter($langCode);

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

    public function format(Offer $offer): string
    {
        $dateFrom = $offer->getStartDate();
        $dateEnd = $offer->getEndDate();

        if (DateComparison::onSameDay($dateFrom, $dateEnd)) {
            $output = $this->formatSameDay($dateFrom);
        } else {
            $output = $this->formatMoreDays($dateFrom, $dateEnd);
        }

        return $output;
    }

    private function formatSameDay(DateTimeInterface $dateFrom): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlDateDayFrom = $this->formatter->formatAsDayOfWeek($dateFrom);

        return (new PlainTextSummaryBuilder($this->trans))
            ->add($intlDateDayFrom)
            ->add($intlDateFrom)
            ->toString();
    }

    private function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlDateDayFrom = $this->formatter->formatAsDayOfWeek($dateFrom);

        $intlDateEnd = $this->formatter->formatAsFullDate($dateEnd);
        $intlDateDayEnd = $this->formatter->formatAsDayOfWeek($dateEnd);

        return (new PlainTextSummaryBuilder($this->trans))
            ->from($intlDateDayFrom, $intlDateFrom)
            ->till($intlDateDayEnd, $intlDateEnd)
            ->toString();
    }
}
