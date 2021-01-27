<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;

final class MediumPeriodicHTMLFormatter implements PeriodicFormatterInterface
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
        $dateFrom = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlDateFromDay = $this->formatter->formatAsDayOfWeek($dateFrom);

        $dateTo = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $intlDateTo = $this->formatter->formatAsFullDate($dateTo);

        if ($intlDateFrom == $intlDateTo) {
            return '<span class="cf-weekday cf-meta">' . ucfirst($intlDateFromDay) . '</span>'
                . ' '
                . '<span class="cf-date">' . $intlDateFrom . '</span>';
        }

        return '<span class="cf-from cf-meta">' . ucfirst($this->translator->translate('from'))
            . '</span> <span class="cf-date">' . $intlDateFrom . '</span> '
            . '<span class="cf-to cf-meta">' . $this->translator->translate('till')
            . '</span> <span class="cf-date">'. $intlDateTo . '</span>';
    }
}
