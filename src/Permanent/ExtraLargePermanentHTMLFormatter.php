<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\HtmlDeviatingDaysFormatter;
use CultuurNet\CalendarSummaryV3\HtmlSummaryFormatter;
use CultuurNet\CalendarSummaryV3\HtmlWeekSchemeFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;

final class ExtraLargePermanentHTMLFormatter implements PermanentFormatterInterface
{
    private Translator $translator;

    private HtmlDeviatingDaysFormatter $deviatingDaysFormatter;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->deviatingDaysFormatter = new HtmlDeviatingDaysFormatter($translator);
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

        $output .= $this->deviatingDaysFormatter->format($offer);

        return HtmlSummaryFormatter::format($output);
    }
}
