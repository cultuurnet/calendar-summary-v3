<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\DateComparison;
use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlStatusFormatter;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeZone;

final class ExtraSmallMultipleHTMLFormatter implements MultipleFormatterInterface
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
        $dateFrom = $offer->getStartDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $dateTo = $offer->getEndDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));

        if (DateComparison::onSameDay($dateFrom, $dateTo)) {
            $output = '<span class="cf-date">' . $this->formatter->formatAsShortDate($dateFrom) . '</span>';
        } else {
            $output = '<span class="cf-from cf-meta">' . ucfirst($this->translator->translate('from')) . '</span> ' .
                '<span class="cf-date">' . $this->formatter->formatAsShortDate($dateFrom) . '</span> ' .
                '<span class="cf-to cf-meta">' . $this->translator->translate('till') . '</span> ' .
                '<span class="cf-date">' . $this->formatter->formatAsShortDate($dateTo) . '</span>';
        }

        $optionalStatus = HtmlStatusFormatter::forOffer($offer, $this->translator)
            ->withBraces()
            ->toString();

        return trim($output . ' ' . $optionalStatus);
    }
}
