<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\OfferFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use DateTimeImmutable;

final class MediumPermanentPlainTextFormatter implements OfferFormatter
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
        $output = '';
        if ($offer->getOpeningHours()) {
            $output .= $this->generateWeekScheme($offer->getOpeningHours());
        } else {
            $output .= ucfirst($this->trans->getTranslations()->t('always_open')) . PHP_EOL;
        }

        return $output;
    }

    /**
     * @param OpeningHours[] $openingHoursData
     * @return string
     */
    private function generateWeekScheme(array $openingHoursData): string
    {
        $outputWeek = ucfirst($this->trans->getTranslations()->t('open')) . ' ';
        // Create an array with formatted days.
        $formattedDays = [];

        foreach ($openingHoursData as $openingHours) {
            $daysOfWeek = $openingHours->getDaysOfWeek();
            foreach ($daysOfWeek as $i => $dayOfWeek) {
                $translatedDay = $this->formatter->formatAsAbbreviatedDayOfWeek(new DateTimeImmutable($dayOfWeek));

                if (!isset($formattedDays[$dayOfWeek])) {
                    $formattedDays[$dayOfWeek] = $translatedDay;
                }
            }
        }

        $i = 0;

        foreach ($formattedDays as $formattedDay) {
            $outputWeek .= $formattedDay;
            if (++$i !== count($formattedDays)) {
                $outputWeek .= ', ';
            }
        }

        return $outputWeek;
    }
}
