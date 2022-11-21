<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\DateFormatter;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\OpeningHourFormatter;
use CultuurNet\CalendarSummaryV3\PlainTextSummaryBuilder;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
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
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->formatter = new DateFormatter($translator->getLocale());
        $this->translator = $translator;
    }

    public function format(Offer $offer): string
    {
        $startDate = $offer->getStartDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $endDate = $offer->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get()));

        $formattedStartDate = $this->formatter->formatAsFullDate($startDate);
        $formattedStartDayName = $this->formatter->formatAsDayOfWeek($startDate);
        $formattedEndDate = $this->formatter->formatAsFullDate($endDate);
        $formattedEndDayName = $this->formatter->formatAsDayOfWeek($endDate);

        $summary = PlainTextSummaryBuilder::start($this->translator)
            ->from($formattedStartDayName, $formattedStartDate)
            ->to($formattedEndDayName, $formattedEndDate);

        if ($offer->getOpeningHours()) {
            $summary = $summary
                ->startNewLine()
                ->append($this->generateWeekScheme($offer->getOpeningHours()));
        }

        return $summary->appendAvailability($offer->getStatus(), $offer->getBookingAvailability())->toString();
    }

    /**
     * @param OpeningHour[] $openingHoursData
     */
    private function generateWeekScheme(array $openingHoursData): string
    {
        /** @var PlainTextSummaryBuilder[] $formattedDays */
        $formattedDays = [];

        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDaysOfWeek() as $dayName) {
                if (!isset($formattedDays[$dayName])) {
                    $translatedDay = $this->formatter->formatAsDayOfWeek(new DateTimeImmutable($dayName));

                    $formattedDays[$dayName] = PlainTextSummaryBuilder::start($this->translator)
                        ->lowercaseNextFirstCharacter()
                        ->append($translatedDay)
                        ->fromHour(OpeningHourFormatter::format($openingHours->getOpens()))
                        ->tillHour(OpeningHourFormatter::format($openingHours->getCloses()));

                    continue;
                }

                $formattedDays[$dayName] = $formattedDays[$dayName]
                    ->and()
                    ->fromHour(OpeningHourFormatter::format($openingHours->getOpens()))
                    ->tillHour(OpeningHourFormatter::format($openingHours->getCloses()));
            }
        }

        return '(' . implode(', ', $formattedDays) . ')';
    }
}
