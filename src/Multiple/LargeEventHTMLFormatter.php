<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\OfferFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\CalendarSummaryV3\Single\LargeSingleHTMLFormatter;
use CultuurNet\SearchV3\ValueObjects\Offer;

final class LargeEventHTMLFormatter implements OfferFormatter
{
    /**
     * @var string $langCode
     */
    private $langCode;

    /**
     * @var bool $hidePast
     */
    private $hidePast;

    public function __construct(string $langCode, bool $hidePastDates)
    {
        $this->langCode = $langCode;
        $this->hidePast = $hidePastDates;
    }

    public function format(Offer $offer): string
    {
        $subEvents = $offer->getSubEvents();
        $output = '<ul class="cnw-event-date-info">';
        $now = new \DateTime();

        foreach ($subEvents as $subEvent) {
            $formatter = new LargeSingleHTMLFormatter($this->langCode);

            $offer = new Event();
            $offer->setStartDate($subEvent->getStartDate());
            $offer->setEndDate($subEvent->getEndDate());

            if (!$this->hidePast ||
                $subEvent->getEndDate()->setTimezone(new \DateTimeZone(date_default_timezone_get())) > $now) {
                $output .= '<li>' . $formatter->format($offer) . '</li>';
            }
        }

        $output .= '</ul>';

        return $output;
    }
}
