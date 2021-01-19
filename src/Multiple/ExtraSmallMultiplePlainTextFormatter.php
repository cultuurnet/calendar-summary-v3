<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;

class ExtraSmallMultiplePlainTextFormatter implements MultipleFormatterInterface
{
    /**
     * @var ExtraSmallMultipleHTMLFormatter
     */
    protected $htmlFormatter;

    public function __construct(string $langCode)
    {
        $this->htmlFormatter = new ExtraSmallMultipleHTMLFormatter($langCode);
    }

    public function format(Event $event): string
    {
        return strip_tags($this->htmlFormatter->format($event));
    }
}
