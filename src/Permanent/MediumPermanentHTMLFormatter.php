<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use IntlDateFormatter;

final class MediumPermanentHTMLFormatter implements PermanentFormatterInterface
{
    /**
     * @var IntlDateFormatter
     */
    private $fmtShortDays;

    /**
     * @var Translator
     */
    private $trans;

    public function __construct(string $langCode)
    {
        $this->fmtShortDays = new IntlDateFormatter(
            $langCode,
            IntlDateFormatter::FULL,
            IntlDateFormatter::FULL,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN,
            'EEE'
        );

        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

    public function format(Offer $offer): string
    {
        $output = '';
        if ($offer->getOpeningHours()) {
            $output .= $this->generateWeekScheme($offer->getOpeningHours());
        } else {
            $output .= '<p class="cf-openinghours">' .
                ucfirst($this->trans->getTranslations()->t('always_open')) . '</p>';
        }

        return $output;
    }

    /**
     * Generate a weekscheme based on the given opening hours.
     *
     * @param OpeningHours[] $openingHoursData
     * @return string
     */
    private function generateWeekScheme(array $openingHoursData): string
    {
        $outputWeek = '<span>' . ucfirst($this->trans->getTranslations()->t('open')) . ' '
            . '<span class="cf-weekdays">';
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
            $outputWeek .= '<span class="cf-weekday-open">' . $formattedDay . '</span>';
            if (++$i !== count($formattedDays)) {
                $outputWeek .= ', ';
            }
        }

        $outputWeek .= '</span></span>';

        return $outputWeek;
    }
}
