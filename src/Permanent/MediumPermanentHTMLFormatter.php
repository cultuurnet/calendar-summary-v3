<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\TranslatedStatusReasonFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use CultuurNet\SearchV3\ValueObjects\Status;
use DateTimeImmutable;

final class MediumPermanentHTMLFormatter implements PermanentFormatterInterface
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
        if ($offer->getStatus()->getType() !== 'Available') {
            $statusText = $offer->getStatus()->getType() === 'Unavailable' ?
                $this->translator->translate('cancelled') :
                $this->translator->translate('postponed');

            $reasonFormatter = new TranslatedStatusReasonFormatter($this->translator);
            $titleAttribute = $reasonFormatter->formatAsTitleAttribute($offer->getStatus());

            return '<p ' . $titleAttribute . 'class="cf-openinghours">' . $statusText . '</p>';
        }

        if ($offer->getOpeningHours()) {
            return $this->generateWeekScheme($offer->getOpeningHours());
        }

        return '<p class="cf-openinghours">' .
            ucfirst($this->translator->translate('always_open')) . '</p>';
    }

    /**
     * Generate a weekscheme based on the given opening hours.
     *
     * @param OpeningHours[] $openingHoursData
     * @return string
     */
    private function generateWeekScheme(array $openingHoursData): string
    {
        $outputWeek = '<span>' . ucfirst($this->translator->translate('open')) . ' '
            . '<span class="cf-weekdays">';
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
            $outputWeek .= '<span class="cf-weekday-open">' . $formattedDay . '</span>';
            if (++$i !== count($formattedDays)) {
                $outputWeek .= ', ';
            }
        }

        $outputWeek .= '</span></span>';

        return $outputWeek;
    }
}
