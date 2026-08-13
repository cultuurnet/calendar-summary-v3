<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\PlainTextPeriodsFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\PlainTextWeekSchemeFormatter;
use CultuurNet\CalendarSummaryV3\Translator;

final class ExtraLargePermanentPlainTextFormatter implements PermanentFormatterInterface
{
    private Translator $translator;

    private PlainTextPeriodsFormatter $periodsFormatter;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->periodsFormatter = new PlainTextPeriodsFormatter($translator);
    }

    public function format(Offer $offer): string
    {
        if ($offer->getStatus()->getType() === 'Unavailable') {
            return ucfirst($this->translator->translate('cancelled'));
        }

        if ($offer->getStatus()->getType() === 'TemporarilyUnavailable') {
            return ucfirst($this->translator->translate('postponed'));
        }

        if (!$offer->getOpeningHours()->isEmpty()) {
            $output = PlainTextWeekSchemeFormatter::forOpeningHours($offer->getOpeningHours(), $this->translator)
                ->withChildcare()
                ->toString();
        } else {
            $output = PlainTextSummaryBuilder::start($this->translator)
                ->alwaysOpen()
                ->toString();
        }

        return $output . $this->periodsFormatter->format($offer) . PHP_EOL;
    }
}
