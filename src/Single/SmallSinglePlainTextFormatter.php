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
        $dateFromDay = $this->formatter->formatAsDayNumber($dateFrom);
        $dateFromMonth = $this->formatter->formatAsAbbreviatedMonthName($dateFrom);

        return (new PlainTextSummaryBuilder($this->trans))
            ->add($dateFromDay)
            ->add($dateFromMonth)
            ->toString();
    }

    private function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $dateFromDay = $this->formatter->formatAsDayNumber($dateFrom);
        $dateFromMonth = $this->formatter->formatAsAbbreviatedMonthName($dateFrom);

        $dateEndDay = $this->formatter->formatAsDayNumber($dateEnd);
        $dateEndMonth = $this->formatter->formatAsAbbreviatedMonthName($dateEnd);

        return (new PlainTextSummaryBuilder($this->trans))
            ->from($dateFromDay, $dateFromMonth)
            ->till($dateEndDay, $dateEndMonth)
            ->toString();
    }
}
