<?php

namespace CultuurNet\CalendarSummaryV3;

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
