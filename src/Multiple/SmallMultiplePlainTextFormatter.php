<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;

class SmallMultiplePlainTextFormatter implements MultipleFormatterInterface
{
    /**
     * @var SmallMultipleHTMLFormatter
     */
    protected $htmlFormatter;

    public function __construct(string $langCode)
    {
        $this->htmlFormatter = new SmallMultipleHTMLFormatter($langCode);
    }

    public function format(Event $event): string
    {
        $html = $this->htmlFormatter->format($event);
        $withLineBreaks = str_replace('</li>', PHP_EOL, $html);
        $withoutHtmlTags = strip_tags($withLineBreaks);
        $withoutLineBreaksAtStartOrEnd = trim($withoutHtmlTags, PHP_EOL);
        return $withoutLineBreaksAtStartOrEnd;
    }
}
