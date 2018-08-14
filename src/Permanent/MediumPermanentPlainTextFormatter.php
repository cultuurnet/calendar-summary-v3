<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\Offer;

/**
 * Provide a large plain text formatter for permanent calendar type.
 * @package CultuurNet\CalendarSummaryV3\Permanent
 */
class MediumPermanentPlainTextFormatter extends MediumPermanentFormatter implements PermanentFormatterInterface
{

    /**
     * {@inheritdoc}
     */
    public function format(Offer $offer)
    {
        $output = '';
        if ($offer->getOpeningHours()) {
            $output .= $this->generateWeekScheme($offer->getOpeningHours());
        }
        return $output;
    }

    /**
     * @param $openingHoursData
     * @return string
     */
    protected function generateWeekScheme($openingHoursData)
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
