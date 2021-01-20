<?php

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
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

        if ($intlStartTimeFrom === '00:00' && $intlEndTimeEnd === '23:59') {
            return ucfirst($intlWeekDayFrom) . ' ' . $intlDateFrom;
        }

        if ($intlStartTimeFrom == $intlEndTimeEnd) {
            return ucfirst($intlWeekDayFrom) . ' ' . $intlDateFrom . ' '
                . $this->trans->getTranslations()->t('at') . ' ' . $intlStartTimeFrom;
        }

        return ucfirst($intlWeekDayFrom) . ' ' . $intlDateFrom
            . ' ' . $this->trans->getTranslations()->t('from') . ' '
            . $intlStartTimeFrom
            . ' ' . $this->trans->getTranslations()->t('till') . ' ' . $intlEndTimeEnd;
    }

    private function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlWeekDayFrom = $this->formatter->formatAsDayOfWeek($dateFrom);
        $intlStartTimeFrom = $this->formatter->formatAsTime($dateFrom);

        $intlDateEnd = $this->formatter->formatAsFullDate($dateEnd);
        $intlWeekDayEnd = $this->formatter->formatAsDayOfWeek($dateEnd);
        $intlEndTimeEnd = $this->formatter->formatAsTime($dateEnd);

        return ucfirst(
            $this->trans->getTranslations()->t('from') . ' '
            . $intlWeekDayFrom . ' ' . $intlDateFrom . ' '
            . $this->trans->getTranslations()->t('at') . ' ' . $intlStartTimeFrom . ' '
            . $this->trans->getTranslations()->t('till') . ' '
            . $intlWeekDayEnd . ' ' . $intlDateEnd . ' '
            . $this->trans->getTranslations()->t('at') . ' ' . $intlEndTimeEnd
        );

        return $output;
    }
}
