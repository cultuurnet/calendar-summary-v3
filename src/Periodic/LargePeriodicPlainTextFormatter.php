<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\OpeningHourFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use DateTime;
use DateTimeImmutable;

final class LargePeriodicPlainTextFormatter implements PeriodicFormatterInterface
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
        $this->trans = new Translator($langCode);
    }

    public function format(Offer $offer): string
    {
        $startDate = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $endDate = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        $formattedStartDate = $this->formatter->formatAsFullDate($startDate);
        $formattedEndDate = $this->formatter->formatAsFullDate($endDate);

        $summary = PlainTextSummaryBuilder::start($this->trans)
            ->from($formattedStartDate)
            ->till($formattedEndDate);

        if ($offer->getOpeningHours()) {
            $summary = $summary
                ->startNewLine()
                ->append($this->generateWeekScheme($offer->getOpeningHours()));
        }

        return $summary->toString();
    }

    private function generateDates(DateTime $dateFrom, DateTime $dateTo): string
    {
        $intlDateFrom = $this->formatter->formatAsFullDate($dateFrom);
        $intlDateTo = $this->formatter->formatAsFullDate($dateTo);

        return ucfirst($this->trans->getTranslations()->t('from')) . ' '
            . $intlDateFrom . ' ' . $this->trans->getTranslations()->t('till') . ' ' . $intlDateTo;
    }

    /**
     * @param OpeningHours[] $openingHoursData
     * @return string
     */
    private function generateWeekScheme(array $openingHoursData): string
    {
        /** @var PlainTextSummaryBuilder[] $formattedDays */
        $formattedDays = [];

        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDaysOfWeek() as $dayName) {
                if (!isset($formattedDays[$dayName])) {
                    $translatedDay = $this->formatter->formatAsDayOfWeek(new DateTimeImmutable($dayName));

                    $formattedDays[$dayName] = PlainTextSummaryBuilder::start($this->trans)
                        ->lowercaseNextFirstCharacter()
                        ->append($translatedDay)
                        ->from(OpeningHourFormatter::format($openingHours->getOpens()))
                        ->till(OpeningHourFormatter::format($openingHours->getCloses()));

                    continue;
                }

                $formattedDays[$dayName] = $formattedDays[$dayName]
                    ->and()
                    ->from(OpeningHourFormatter::format($openingHours->getOpens()))
                    ->till(OpeningHourFormatter::format($openingHours->getCloses()));
            }
        }

        return '(' . implode(', ', $formattedDays) . ')';
    }
}
