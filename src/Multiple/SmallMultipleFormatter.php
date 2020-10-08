<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

abstract class SmallMultipleFormatter
{
    /**
     * @var string
     */
    protected $langCode;

    public function __construct(string $langCode)
    {
        $this->langCode = $langCode;
    }
}
