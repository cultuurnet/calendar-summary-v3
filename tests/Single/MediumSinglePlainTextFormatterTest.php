<?php

namespace CultuurNet\CalendarSummaryV3\Tests\Single;

use CultuurNet\CalendarSummaryV3\Single\MediumSinglePlainTextFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;
use PHPUnit\Framework\TestCase;

class MediumSinglePlainTextFormatterTest extends TestCase
{
    /**
     * @var MediumSinglePlainTextFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MediumSinglePlainTextFormatter('nl_NL');
    }

    public function testFormatHTMLSingleDateMediumOneDay(): void
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-25T21:30:00+01:00'));

        $this->assertEquals(
            'donderdag 25 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateMediumWithLeadingZeroOneDay(): void
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-08T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            'maandag 8 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateMediumMoreDays(): void
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-25T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-27T21:30:00+01:00'));

        $this->assertEquals(
            'van donderdag 25 januari 2018 tot zaterdag 27 januari 2018',
            $this->formatter->format($event)
        );
    }

    public function testFormatHTMLSingleDateMediumWithLeadingZeroMoreDays(): void
    {
        $event = new Event();
        $event->setStartDate(new \DateTime('2018-01-06T20:00:00+01:00'));
        $event->setEndDate(new \DateTime('2018-01-08T21:30:00+01:00'));

        $this->assertEquals(
            'van zaterdag 6 januari 2018 tot maandag 8 januari 2018',
            $this->formatter->format($event)
        );
    }
}
