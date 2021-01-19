<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\SearchV3\ValueObjects\Event;

class LargeMultiplePlainTextFormatter implements MultipleFormatterInterface
{
    /**
     * @var LargeMultipleHTMLFormatter
     */
    protected $htmlFormatter;

    public function __construct(string $langCode, bool $hidePastDates)
    {
        $this->htmlFormatter = new LargeMultipleHTMLFormatter($langCode, $hidePastDates);
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
