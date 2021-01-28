<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use CultuurNet\SearchV3\ValueObjects\Place;
use CultuurNet\SearchV3\ValueObjects\Status;
use PHPUnit\Framework\TestCase;

/**
 * Provide unit tests for large HTML permanent formatter.
 * @package CultuurNet\CalendarSummaryV3\Permanent
 */
class MediumPermanentHTMLFormatterTest extends TestCase
{
    /**
     * @var MediumPermanentHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new MediumPermanentHTMLFormatter(new Translator('nl_NL'));
    }

    public function testFormatASimplePermanent(): void
    {
        $place = new Place();
        $place->setStatus(new Status('Available'));

        $place->setStartDate(new \DateTime('25-11-2025'));
        $place->setEndDate(new \DateTime('30-11-2030'));

        $openingHours1 = new OpeningHours();
        $openingHours1->setDaysOfWeek(['monday','tuesday', 'wednesday']);
        $openingHours1->setOpens('00:00');
        $openingHours1->setCloses('13:00');

        $openingHours2 = new OpeningHours();
        $openingHours2->setDaysOfWeek(['friday']);
        $openingHours2->setOpens('09:00');
        $openingHours2->setCloses('13:00');

        $openingHours3 = new OpeningHours();
        $openingHours3->setDaysOfWeek(['saturday', 'sunday']);
        $openingHours3->setOpens('09:00');
        $openingHours3->setCloses('19:00');

        $openingHoursData = [$openingHours1, $openingHours2, $openingHours3];

        $place->setOpeningHours($openingHoursData);

        $this->assertEquals(
            '<span>Open op <span class="cf-weekdays">'
            . '<span class="cf-weekday-open">ma</span>, '
            . '<span class="cf-weekday-open">di</span>, '
            . '<span class="cf-weekday-open">wo</span>, '
            . '<span class="cf-weekday-open">vr</span>, '
            . '<span class="cf-weekday-open">za</span>, '
            . '<span class="cf-weekday-open">zo</span>'
            . '</span></span>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAMixedPermanent(): void
    {
        $place = new Place();
        $place->setStatus(new Status('Available'));

        $openingHours1 = new OpeningHours();
        $openingHours1->setDaysOfWeek(['monday','tuesday', 'wednesday']);
        $openingHours1->setOpens('09:00');
        $openingHours1->setCloses('13:00');

        $openingHours2 = new OpeningHours();
        $openingHours2->setDaysOfWeek(['monday','tuesday', 'wednesday']);
        $openingHours2->setOpens('17:00');
        $openingHours2->setCloses('20:00');

        $openingHours3 = new OpeningHours();
        $openingHours3->setDaysOfWeek(['friday', 'saturday']);
        $openingHours3->setOpens('10:00');
        $openingHours3->setCloses('15:00');

        $openingHours4 = new OpeningHours();
        $openingHours4->setDaysOfWeek(['friday', 'saturday']);
        $openingHours4->setOpens('18:00');
        $openingHours4->setCloses('21:00');

        $openingHoursData = [$openingHours1, $openingHours2, $openingHours3, $openingHours4];
        $place->setOpeningHours($openingHoursData);

        $this->assertEquals(
            '<span>Open op <span class="cf-weekdays">'
            . '<span class="cf-weekday-open">ma</span>, '
            . '<span class="cf-weekday-open">di</span>, '
            . '<span class="cf-weekday-open">wo</span>, '
            . '<span class="cf-weekday-open">vr</span>, '
            . '<span class="cf-weekday-open">za</span>'
            . '</span></span>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAComplexPermanent(): void
    {
        $place = new Place();
        $place->setStatus(new Status('Available'));

        $openingHours1 = new OpeningHours();
        $openingHours1->setDaysOfWeek(['monday','tuesday']);
        $openingHours1->setOpens('09:30');
        $openingHours1->setCloses('13:45');

        $openingHours2 = new OpeningHours();
        $openingHours2->setDaysOfWeek(['monday']);
        $openingHours2->setOpens('17:00');
        $openingHours2->setCloses('20:00');

        $openingHours3 = new OpeningHours();
        $openingHours3->setDaysOfWeek(['tuesday']);
        $openingHours3->setOpens('18:00');
        $openingHours3->setCloses('20:00');

        $openingHours4 = new OpeningHours();
        $openingHours4->setDaysOfWeek(['tuesday']);
        $openingHours4->setOpens('21:00');
        $openingHours4->setCloses('23:00');

        $openingHours5 = new OpeningHours();
        $openingHours5->setDaysOfWeek(['friday', 'saturday']);
        $openingHours5->setOpens('10:00');
        $openingHours5->setCloses('15:00');

        $openingHoursData = [$openingHours1, $openingHours2, $openingHours3, $openingHours4, $openingHours5];
        $place->setOpeningHours($openingHoursData);

        $this->assertEquals(
            '<span>Open op <span class="cf-weekdays">'
            . '<span class="cf-weekday-open">ma</span>, '
            . '<span class="cf-weekday-open">di</span>, '
            . '<span class="cf-weekday-open">vr</span>, '
            . '<span class="cf-weekday-open">za</span>'
            . '</span></span>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAnUnavailablePermanent(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Unavailable'));

        $this->assertEquals(
            '<p class="cf-openinghours">Geannuleerd</p>',
            $this->formatter->format($event)
        );
    }

    public function testFormatATemporarilyUnavailablePermanent(): void
    {
        $event = new Event();
        $event->setStatus(new Status('TemporarilyUnavailable'));

        $this->assertEquals(
            '<p class="cf-openinghours">Uitgesteld</p>',
            $this->formatter->format($event)
        );
    }
}
