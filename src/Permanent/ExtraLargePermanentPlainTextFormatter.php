<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\PlainTextDeviatingDaysFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\PlainTextWeekSchemeFormatter;
use CultuurNet\CalendarSummaryV3\Translator;

final class ExtraLargePermanentPlainTextFormatter implements PermanentFormatterInterface
{
    private Translator $translator;

    private PlainTextDeviatingDaysFormatter $deviatingDaysFormatter;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
        $this->deviatingDaysFormatter = new PlainTextDeviatingDaysFormatter($translator);
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
                ->withoutClosedDays()
                ->toString();
        } else {
            $output = PlainTextSummaryBuilder::start($this->translator)
                ->alwaysOpen()
                ->toString();
        }

        // The days without opening hours are listed among the closed days instead of in
        // the week scheme.
        $deviatingDays = $this->deviatingDaysFormatter->format(
            $offer,
            $offer->getOpeningHours()->closedDaysOfWeek()
        );

        return $output . $deviatingDays . PHP_EOL;
    }
}
