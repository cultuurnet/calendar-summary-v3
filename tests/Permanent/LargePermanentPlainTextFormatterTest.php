<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use CultuurNet\SearchV3\ValueObjects\Place;

class LargePermanentPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargePermanentPlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new LargePermanentPlainTextFormatter();
    }

    public function testFormatASimplePermanent()
    {
        $place = new Place();
        $place->setStartDate(new \DateTime('25-11-2025'));
        $place->setEndDate(new \DateTime('30-11-2030'));

        $openingHours1 = new OpeningHours();
        $openingHours1->setDaysOfWeek(['monday','tuesday', 'wednesday']);
        $openingHours1->setOpens('09:00');
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
            'Ma Van 9:00 tot 13:00'. PHP_EOL . 'Di Van 9:00 tot 13:00'. PHP_EOL . 'Wo Van 9:00 tot 13:00' . PHP_EOL
            . 'Do  gesloten'. PHP_EOL . 'Vr Van 9:00 tot 13:00'. PHP_EOL . 'Za Van 9:00 tot 19:00'
            . PHP_EOL . 'Zo Van 9:00 tot 19:00' . PHP_EOL,
            $this->formatter->format($place)
        );
    }
}
