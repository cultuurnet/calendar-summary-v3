<?php

namespace CultuurNet\CalendarSummary;

class CalendarPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CalendarPlainTextFormatter
     */
    protected $formatter;


    public function setUp()
    {
        $this->formatter = new CalendarPlainTextFormatter();
    }
}
