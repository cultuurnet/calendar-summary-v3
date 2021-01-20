<?php

namespace CultuurNet\CalendarSummaryV3\Single;

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
        $dateFrom = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $dateEnd = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        if ($dateFrom->format('Y-m-d') == $dateEnd->format('Y-m-d')) {
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
            $output = $intlWeekDayFrom . ' ' . $intlDateFrom;
        } elseif ($intlStartTimeFrom == $intlEndTimeEnd) {
            $output = $intlWeekDayFrom . ' ' . $intlDateFrom . ' ';
            $output .= $this->trans->getTranslations()->t('at') . ' ' . $intlStartTimeFrom;
        } else {
            $output = $intlWeekDayFrom . ' ' . $intlDateFrom;
            $output .= ' ' . $this->trans->getTranslations()->t('from') . ' ';
            $output .= $intlStartTimeFrom;
            $output .= ' ' . $this->trans->getTranslations()->t('till') . ' ' . $intlEndTimeEnd;
        }

        return $output;
    }

    private function formatMoreDays(DateTimeInterface $dateFrom, DateTimeInterface $dateEnd): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlWeekDayFrom = $this->formatter->formatAsDayOfWeek($dateFrom);
        $intlStartTimeFrom = $this->formatter->formatAsTime($dateFrom);

        $intlDateEnd = $this->formatter->formatAsFullDate($dateEnd);
        $intlWeekDayEnd = $this->formatter->formatAsDayOfWeek($dateEnd);
        $intlEndTimeEnd = $this->formatter->formatAsTime($dateEnd);

        $output = ucfirst($this->trans->getTranslations()->t('from')) . ' ';
        $output .= $intlWeekDayFrom . ' ' . $intlDateFrom . ' ' . $intlStartTimeFrom;
        $output .= ' ' . $this->trans->getTranslations()->t('till') . ' ';
        $output .= $intlWeekDayEnd . ' ' . $intlDateEnd . ' ' . $intlEndTimeEnd;

        return $output;
    }
}
