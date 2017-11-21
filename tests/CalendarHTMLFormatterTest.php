<?php

namespace CultuurNet\CalendarSummary;

class CalendarHTMLFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CalendarHTMLFormatter
     */
    protected $formatter;


    public function setUp()
    {
        $this->formatter = new CalendarHTMLFormatter();
    }
}
