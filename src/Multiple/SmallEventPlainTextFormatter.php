<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\OfferFormatter;
use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicPlainTextFormatter;
use CultuurNet\SearchV3\ValueObjects\Offer;

final class SmallEventPlainTextFormatter implements OfferFormatter
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
        $formatter = new MediumPeriodicPlainTextFormatter($this->langCode);
        return $formatter->format($offer);
    }
}
