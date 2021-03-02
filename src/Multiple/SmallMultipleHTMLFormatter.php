<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Periodic\MediumPeriodicHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Translator;

final class SmallMultipleHTMLFormatter implements MultipleFormatterInterface
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function format(Offer $offer): string
    {
        $formatter = new MediumPeriodicHTMLFormatter($this->translator);
        return $formatter->format($offer);
    }
}
