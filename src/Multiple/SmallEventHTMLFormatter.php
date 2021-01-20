<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\OfferFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicHTMLFormatter;
use CultuurNet\SearchV3\ValueObjects\Offer;

final class SmallEventHTMLFormatter implements OfferFormatter
{
    /**
     * @var string
     */
    private $langCode;

    public function __construct(string $langCode)
    {
        $this->langCode = $langCode;
    }

    public function format(Offer $offer): string
    {
        $formatter = new MediumPeriodicHTMLFormatter($this->langCode);
        return $formatter->format($offer);
    }
}
