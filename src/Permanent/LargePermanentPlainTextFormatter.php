<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
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
    private $trans;

    public function __construct(string $langCode)
    {
        $this->formatter = new DateFormatter($langCode);

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

    public function format(Offer $offer): string
    {
        if ($offer->getOpeningHours()) {
            return $this->generateWeekScheme($offer->getOpeningHours());
        }

        return (new PlainTextSummaryBuilder($this->trans))
            ->addTranslation('always_open')
            ->startNewLine()
            ->toString();
    }

    private function getFormattedTime(string $time): string
    {
        $formattedShortTime = ltrim($time, '0');
        if ($formattedShortTime == ':00') {
            $formattedShortTime = '0:00';
        }
        return $formattedShortTime;
    }

    /**
     * @param OpeningHours[] $openingHoursData
     * @return string
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
            $formattedDays[$dayName] = (new PlainTextSummaryBuilder($this->trans))
                ->add($this->formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($dayName)));
        }

        // Keep track of which day (names) have opening hours, so we know which days are closed.
        $daysWithOpeningHours = [];

        // Loop over every 'from ... till ...' and add it to the right day(s)
        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDaysOfWeek() as $dayName) {
                $daysWithOpeningHours[] = $dayName;

                $formattedDays[$dayName] = $formattedDays[$dayName]
                    ->addTranslation('from')
                    ->add($this->getFormattedTime($openingHours->getOpens()))
                    ->addTranslation('till')
                    ->add($this->getFormattedTime($openingHours->getCloses()))
                    ->startNewLineWithLowercaseFirstCharacter();
            }
        }

        // Add 'closed' to every day without opening hours.
        $daysWithOpeningHours = array_unique($daysWithOpeningHours);
        $closedDays = array_diff($dayNames, $daysWithOpeningHours);
        foreach ($closedDays as $closedDayName) {
            $formattedDays[$closedDayName] = $formattedDays[$closedDayName]
                ->addTranslation('closed')
                ->startNewLine();
        }

        // Combine the opening info of each day together into a single string.
        return implode('', $formattedDays);
    }
}
