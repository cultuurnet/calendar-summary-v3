<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use CultuurNet\SearchV3\ValueObjects\Place;

class LargePeriodicPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargePeriodicPlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new LargePeriodicPlainTextFormatter();
    }

    public function testFormatAPeriodWithSingleTimeBlocks()
    {
        $place = new Place();
        $place->setStartDate(new \DateTime('25-11-2025'));
        $place->setEndDate(new \DateTime('30-11-2030'));

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

        $weekscheme = [$openingHours1, $openingHours2, $openingHours3];

        $place->setOpeningHours($weekscheme);

        $this->assertEquals(
            'Van 25 november 2025 tot 30 november 2030'. PHP_EOL
            . '(ma van 9:00 tot 13:00'. PHP_EOL . 'van 17:00 tot 20:00,' . PHP_EOL
            . 'di van 9:00 tot 13:00'. PHP_EOL . 'van 17:00 tot 20:00,'. PHP_EOL
            . 'wo van 9:00 tot 13:00' . PHP_EOL . 'van 17:00 tot 20:00,'. PHP_EOL
            . 'do  gesloten,' . PHP_EOL
            . 'vr van 10:00 tot 15:00'. PHP_EOL
            . 'za van 10:00 tot 15:00' . PHP_EOL
            . 'zo  gesloten)',
            $this->formatter->format($place)
        );
    }

    /*
    public function testFormatsAPeriodListWithoutUnnecessaryLineBreak()
    {
        $period_list = new CultureFeed_Cdb_Data_Calendar_PeriodList();
        $period = new CultureFeed_Cdb_Data_Calendar_Period('2020-09-20', '2020-09-21');
        $period_list->add($period);

        $expectedResult = 'Van 20 september 2020 tot 21 september 2020';

        $this->assertEquals(
            $expectedResult,
            $this->formatter->format($period_list)
        );
    }*/
}
