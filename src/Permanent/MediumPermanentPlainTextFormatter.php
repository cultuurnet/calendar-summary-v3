<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;

class MediumPermanentPlainTextFormatter extends MediumPermanentFormatter implements PermanentFormatterInterface
{
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
    protected function generateWeekScheme(array $openingHoursData): string
    {
        $outputWeek = ucfirst($this->trans->getTranslations()->t('open')) . ' ';
        // Create an array with formatted days.
        $formattedDays = [];

        foreach ($openingHoursData as $openingHours) {
            $daysOfWeek = $openingHours->getDaysOfWeek();
            foreach ($daysOfWeek as $i => $dayOfWeek) {
                $translatedDay = $this->fmtShortDays->format(strtotime($dayOfWeek));

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
