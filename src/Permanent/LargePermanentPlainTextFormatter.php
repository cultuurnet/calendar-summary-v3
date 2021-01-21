<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use DateTimeImmutable;

final class LargePermanentPlainTextFormatter implements PermanentFormatterInterface
{
    private $daysOfWeek = array(
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday'
    );

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

        return ucfirst($this->trans->getTranslations()->t('always_open')) . PHP_EOL;
    }

    private function getFormattedTime(string $time): string
    {
        $timeParts = explode(':', $time);
        $hour = ltrim($timeParts[0], '0');
        if ($hour === '') {
            $hour = '0';
        }
        $timeParts[0] = $hour;
        return implode(':', $timeParts);
    }

    /**
     * @param OpeningHours[] $openingHoursData
     * @return string
     */
    private function generateWeekScheme(array $openingHoursData): string
    {
        $outputWeek = '';
        // Create an array with formatted days.
        $formattedDays = [];

        foreach ($openingHoursData as $openingHours) {
            $daysOfWeek = $openingHours->getDaysOfWeek();
            foreach ($daysOfWeek as $i => $dayOfWeek) {
                $translatedDay = $this->formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($dayOfWeek));

                if (!isset($formattedDays[$dayOfWeek])) {
                    $formattedDays[$dayOfWeek] = ucfirst($translatedDay)
                        . ' ' . $this->trans->getTranslations()->t('from') . ' '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' ' . $this->trans->getTranslations()->t('till') . ' '
                        . $this->getFormattedTime($openingHours->getCloses())
                        . PHP_EOL;
                } else {
                    $formattedDays[$dayOfWeek] .= '' . $this->trans->getTranslations()->t('from') . ' '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' ' . $this->trans->getTranslations()->t('till') . ' '
                        . $this->getFormattedTime($openingHours->getCloses())
                        . PHP_EOL;
                }
            }
        }

        // Create an array with formatted closed days.
        $closedDays = [];
        foreach ($this->daysOfWeek as $day) {
            $closedDays[$day] = ucfirst($this->formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($day)))
                . ' '
                . $this->trans->getTranslations()->t('closed') . PHP_EOL;
        }

        // Merge the formatted days with the closed days array and sort them.
        $sortedDays = array();
        foreach ($this->daysOfWeek as $day) {
            if (isset($formattedDays[$day])) {
                $sortedDays[$day] = $formattedDays[$day];
            } else {
                $sortedDays[$day] = $closedDays[$day];
            }
        }

        // Render the rest of the week scheme output.
        foreach ($sortedDays as $sortedDay) {
            $outputWeek .= $sortedDay;
        }
        return $outputWeek;
    }
}
