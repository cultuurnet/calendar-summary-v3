<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\OfferFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;

final class MediumPeriodicHTMLFormatter implements OfferFormatter
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
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlDateFromDay = $this->formatter->formatAsDayOfWeek($dateFrom);

        $dateTo = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $intlDateTo = $this->formatter->formatAsFullDate($dateTo);

        if ($intlDateFrom == $intlDateTo) {
            $output = '<span class="cf-weekday cf-meta">' . $intlDateFromDay . '</span>';
            $output .= ' ';
            $output .= '<span class="cf-date">' . $intlDateFrom . '</span>';
        } else {
            $output = '<span class="cf-from cf-meta">' . ucfirst($this->trans->getTranslations()->t('from'));
            $output .= '</span> <span class="cf-date">' . $intlDateFrom . '</span> ';
            $output .= '<span class="cf-to cf-meta">' . $this->trans->getTranslations()->t('till');
            $output .= '</span> <span class="cf-date">'. $intlDateTo . '</span>';
        }

        return $output;
    }
}
