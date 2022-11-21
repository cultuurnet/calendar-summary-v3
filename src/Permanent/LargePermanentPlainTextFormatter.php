<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\OpeningHourFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use DateTimeImmutable;

final class LargePermanentPlainTextFormatter implements PermanentFormatterInterface
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
        $dayNames = [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday',
        ];

        // Add day name to the start of each day's week scheme
        $formattedDays = [];
        foreach ($dayNames as $dayName) {
            $formattedDays[$dayName] = PlainTextSummaryBuilder::start($this->translator)
                ->append($this->formatter->formatAsDayOfWeek(new DateTimeImmutable($dayName)));
        }

        // Keep track of which day (names) have opening hours, so we know which days are closed.
        $daysWithOpeningHours = [];

        // Loop over every 'from ... till ...' and add it to the right day(s)
        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDaysOfWeek() as $dayName) {
                $daysWithOpeningHours[] = $dayName;

                $formattedDays[$dayName] = $formattedDays[$dayName]
                    ->fromHour(OpeningHourFormatter::format($openingHours->getOpens()))
                    ->tillHour(OpeningHourFormatter::format($openingHours->getCloses()))
                    ->startNewLine()
                    ->lowercaseNextFirstCharacter();
            }
        }

        // Add 'closed' to every day without opening hours.
        $daysWithOpeningHours = array_unique($daysWithOpeningHours);
        $closedDays = array_diff($dayNames, $daysWithOpeningHours);
        foreach ($closedDays as $closedDayName) {
            $formattedDays[$closedDayName] = $formattedDays[$closedDayName]
                ->closed()
                ->startNewLine();
        }

        // Combine the opening info of each day together into a single string.
        return implode('', $formattedDays);
    }
}
