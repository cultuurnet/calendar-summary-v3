<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\HtmlPeriodsFormatter;
use CultuurNet\CalendarSummaryV3\HtmlSummaryFormatter;
use CultuurNet\CalendarSummaryV3\HtmlWeekSchemeFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeImmutable;

final class ExtraLargePeriodicHTMLFormatter implements PeriodicFormatterInterface
{
    private DateFormatter $formatter;

    private Translator $translator;

    private HtmlPeriodsFormatter $periodsFormatter;

    public function __construct(Translator $translator)
    {
        $this->formatter = new DateFormatter($translator->getLocale());
        $this->translator = $translator;
        $this->periodsFormatter = new HtmlPeriodsFormatter($translator);
    }

    public function format(Offer $offer): string
    {
        $optionalAvailability = HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
            ->withBraces()
            ->toString();

        $output = $this->generateDates(
            $offer->getStartDate(),
            $offer->getEndDate(),
            $optionalAvailability
        );

        if (!$offer->getOpeningHours()->isEmpty()) {
            $output .= HtmlWeekSchemeFormatter::forOpeningHours($offer->getOpeningHours(), $this->translator)
                ->withHeading()
                ->withChildcare()
                ->toString();
        }

        $output .= $this->periodsFormatter->format($offer);

        return trim(HtmlSummaryFormatter::format($output));
    }

    /**
     * @return string
     */
    private function generateDates(DateTimeImmutable $dateFrom, DateTimeImmutable $dateTo, string $optionalStatus)
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlDateTo = $this->formatter->formatAsFullDate($dateTo);

        return '<p class="cf-period">'
            . '<span class="cf-weekday cf-meta">' . $this->formatter->formatAsDayOfWeek($dateFrom) . '</span>'
            . '<time itemprop="startDate" datetime="' . $dateFrom->format('Y-m-d') . '">'
            . '<span class="cf-date">' . $intlDateFrom . '</span> </time>'
            . '<span class="cf-to cf-meta">' . $this->translator->translate('to') . '</span>'
            . '<span class="cf-weekday cf-meta">' . $this->formatter->formatAsDayOfWeek($dateTo) . '</span>'
            . '<time itemprop="endDate" datetime="' . $dateTo->format('Y-m-d') . '">'
            . '<span class="cf-date">' . $intlDateTo . '</span> </time>'
            . $optionalStatus
            . '</p>';
    }
}
