<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\HtmlAdjustedDaysFormatter;
use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\HtmlClosedDaysFormatter;
use CultuurNet\CalendarSummaryV3\HtmlWeekSchemeFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;

final class ExtraLargePermanentHTMLFormatter implements PermanentFormatterInterface
{
    private Translator $translator;

    private HtmlAdjustedDaysFormatter $adjustedDaysFormatter;

    private HtmlClosedDaysFormatter $closedDaysFormatter;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->adjustedDaysFormatter = new HtmlAdjustedDaysFormatter($translator);
        $this->closedDaysFormatter = new HtmlClosedDaysFormatter($translator);
    }

    public function format(Offer $offer): string
    {
        if (!$offer->isAvailable()) {
            return HtmlAvailabilityFormatter::forOffer($offer, $this->translator)
                ->withElement('p')
                ->withoutBraces()
                ->capitalize()
                ->toString();
        }

        if (!$offer->getOpeningHours()->isEmpty()) {
            $output = HtmlWeekSchemeFormatter::forOpeningHours($offer->getOpeningHours(), $this->translator)
                ->withEveryDayOfTheWeek()
                ->withChildcare()
                ->toString();
        } else {
            $output = '<p class="cf-openinghours">'
                . ucfirst($this->translator->translate('open_every_day'))
                . '</p>';
        }

        $output .= $this->adjustedDaysFormatter->format($offer->getAdjustedDays());
        $output .= $this->closedDaysFormatter->format($offer->getClosedDays());

        return $this->formatSummary($output);
    }

    private function formatSummary(string $calsum): string
    {
        $calsum = str_replace('><', '> <', $calsum);
        return str_replace('  ', ' ', $calsum);
    }
}
