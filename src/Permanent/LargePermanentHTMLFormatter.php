<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\HtmlAvailabilityFormatter;
use CultuurNet\CalendarSummaryV3\HtmlSummaryFormatter;
use CultuurNet\CalendarSummaryV3\HtmlWeekSchemeFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;

final class LargePermanentHTMLFormatter implements PermanentFormatterInterface
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
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
            return HtmlSummaryFormatter::format(
                HtmlWeekSchemeFormatter::forOpeningHours($offer->getOpeningHours(), $this->translator)
                    ->withEveryDayOfTheWeek()
                    ->toString()
            );
        }

        return HtmlSummaryFormatter::format(
            '<p class="cf-openinghours">'
            . ucfirst($this->translator->translate('open_every_day'))
            . '</p>'
        );
    }
}
