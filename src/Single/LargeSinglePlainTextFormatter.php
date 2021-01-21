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
        $dateFrom = $offer->getStartDate();
        $dateEnd = $offer->getEndDate();

        if (DateComparison::onSameDay($dateFrom, $dateEnd)) {
            $output = $this->formatSameDay($dateFrom, $dateEnd);
        } else {
            $output = $this->formatMoreDays($dateFrom, $dateEnd);
        }

        return $output;
    }

    private function formatSameDay(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlWeekDayFrom = $this->formatter->formatAsDayOfWeek($dateFrom);
        $intlStartTimeFrom = $this->formatter->formatAsTime($dateFrom);
        $intlEndTimeEnd = $this->formatter->formatAsTime($dateEnd);

        $summaryBuilder = (new PlainTextSummaryBuilder($this->trans))
            ->add($intlWeekDayFrom)
            ->add($intlDateFrom);

        if ($intlStartTimeFrom === '00:00' && $intlEndTimeEnd === '23:59') {
            return $summaryBuilder->toString();
        }

        if ($intlStartTimeFrom === $intlEndTimeEnd) {
            return $summaryBuilder
                ->addTranslation('at')
                ->add($intlStartTimeFrom)
                ->toString();
        }

        return $summaryBuilder
            ->addTranslation('from')
            ->add($intlStartTimeFrom)
            ->addTranslation('till')
            ->add($intlEndTimeEnd);
    }

    private function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlWeekDayFrom = $this->formatter->formatAsDayOfWeek($dateFrom);
        $intlStartTimeFrom = $this->formatter->formatAsTime($dateFrom);

        $intlDateEnd = $this->formatter->formatAsFullDate($dateEnd);
        $intlWeekDayEnd = $this->formatter->formatAsDayOfWeek($dateEnd);
        $intlEndTimeEnd = $this->formatter->formatAsTime($dateEnd);

        return (new PlainTextSummaryBuilder($this->trans))
            ->addTranslation('from')
            ->add($intlWeekDayFrom)
            ->add($intlDateFrom)
            ->addTranslation('at')
            ->add($intlStartTimeFrom)
            ->addTranslation('till')
            ->add($intlWeekDayEnd)
            ->add($intlDateEnd)
            ->addTranslation('at')
            ->add($intlEndTimeEnd);
    }
}
