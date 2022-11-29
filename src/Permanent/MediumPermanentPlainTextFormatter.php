<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeImmutable;

final class MediumPermanentPlainTextFormatter implements PermanentFormatterInterface
{
    use MediumPermanentWeekScheme;

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
        if ($offer->getStatus()->getType() === 'Unavailable') {
            return ucfirst($this->translator->translate('cancelled'));
        }

        if ($offer->getStatus()->getType() === 'TemporarilyUnavailable') {
            return ucfirst($this->translator->translate('postponed'));
        }

        if ($offer->getOpeningHours()) {
            return $this->generateWeekScheme($offer->getOpeningHours());
        }

        return PlainTextSummaryBuilder::start($this->translator)
            ->alwaysOpen()
            ->startNewLine()
            ->toString();
    }

    /**
     * @param OpeningHour[] $openingHoursData
     */
    private function generateWeekScheme(array $openingHoursData): string
    {
        $weekDaysOpen = [];
        // Create a list of all day names that have opening hours
        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDaysOfWeek() as $dayName) {
                if (!in_array($dayName, $weekDaysOpen, true)) {
                    $weekDaysOpen[(int) $this->formatter->formatAsDayOfWeekNumber(new DateTimeImmutable($dayName))] = $dayName;
                }
            }
        }

        if (count($weekDaysOpen) === 7) {
            return PlainTextSummaryBuilder::start($this->translator)
                ->alwaysOpen()
                ->startNewLine()
                ->toString();
        }

        if (count($weekDaysOpen) === 1) {
            return PlainTextSummaryBuilder::start($this->translator)
                ->append($this->translator->translate('open_every'))
                ->append($this->formatter->formatAsDayOfWeek(new DateTimeImmutable(reset($weekDaysOpen))))
                ->append($this->translator->translate('open_every_end'))
                ->toString();
        }

        $weekScheme = $this->getWeekScheme($weekDaysOpen, $this->formatter);

        $isFirstPeriodMin3days = array_pop($weekScheme);

        if ($isFirstPeriodMin3days) {
            return PlainTextSummaryBuilder::start($this->translator)
                ->openAt(...$weekScheme)
                ->toString();
        }

        // Put all the day names with opening hours on a single line with 'Open at' (sec) at the beginning.
        // E.g. 'Open at mo - th & su'

        return PlainTextSummaryBuilder::start($this->translator)
            ->openAt(...$weekScheme)
            ->toString();
    }
}
