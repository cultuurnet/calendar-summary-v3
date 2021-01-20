<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\OfferFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTimeInterface;

final class SmallSinglePlainTextFormatter implements OfferFormatter
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

        $output = $dateFromDay . ' ' . $dateFromMonth;

        return $output;
    }

    private function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $dateFromDay = $this->formatter->formatAsDayNumber($dateFrom);
        $dateFromMonth = $this->formatter->formatAsAbbreviatedMonthName($dateFrom);

        $dateEndDay = $this->formatter->formatAsDayNumber($dateEnd);
        $dateEndMonth = $this->formatter->formatAsAbbreviatedMonthName($dateEnd);

        $output = $this->trans->getTranslations()->t('from') . ' ' . $dateFromDay . ' '
            . $dateFromMonth . ' ' . $this->trans->getTranslations()->t('till') . ' '
            . $dateEndDay . ' ' . $dateEndMonth;

        return $output;
    }
}
