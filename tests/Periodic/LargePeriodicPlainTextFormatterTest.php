<?php

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use CultuurNet\SearchV3\ValueObjects\Place;

/**
 * Provide unit tests for large plain text periodic formatter.
 * @package CultuurNet\CalendarSummaryV3\Periodic
 */
class LargePeriodicPlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargePeriodicPlainTextFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new LargePeriodicPlainTextFormatter('nl_NL');
    }

    public function testFormatAPeriodWithSingleTimeBlocks()
    {
        $place = new Place();
        $place->setStartDate(new \DateTime('25-11-2025'));
        $place->setEndDate(new \DateTime('30-11-2030'));

        $openingHours1 = new OpeningHours();
        $openingHours1->setDaysOfWeek(['monday','tuesday', 'wednesday']);
        $openingHours1->setOpens('00:00');
        $openingHours1->setCloses('17:00');

        $openingHours2 = new OpeningHours();
        $openingHours2->setDaysOfWeek(['friday', 'saturday']);
        $openingHours2->setOpens('10:00');
        $openingHours2->setCloses('18:00');

        $openingHoursData = [$openingHours1, $openingHours2];

        $place->setOpeningHours($openingHoursData);

        $this->assertEquals(
            'Van 25 november 2025 tot en met 30 november 2030'. PHP_EOL
            . '(maandag van 0:00 tot en met 17:00, '
            . 'dinsdag van 0:00 tot en met 17:00, '
            . 'woensdag van 0:00 tot en met 17:00, '
            . 'vrijdag van 10:00 tot en met 18:00, '
            . 'zaterdag van 10:00 tot en met 18:00)',
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithSplitTimeBlocks()
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

        $openingHours4 = new OpeningHours();
        $openingHours4->setDaysOfWeek(['friday', 'saturday']);
        $openingHours4->setOpens('18:00');
        $openingHours4->setCloses('21:00');

        $openingHoursData = [$openingHours1, $openingHours2, $openingHours3, $openingHours4];

        $place->setOpeningHours($openingHoursData);

        $this->assertEquals(
            'Van 25 november 2025 tot en met 30 november 2030'. PHP_EOL
            . '(maandag van 9:00 tot en met 13:00 en van 17:00 tot en met 20:00, '
            . 'dinsdag van 9:00 tot en met 13:00 en van 17:00 tot en met 20:00, '
            . 'woensdag van 9:00 tot en met 13:00 en van 17:00 tot en met 20:00, '
            . 'vrijdag van 10:00 tot en met 15:00 en van 18:00 tot en met 21:00, '
            . 'zaterdag van 10:00 tot en met 15:00 en van 18:00 tot en met 21:00)',
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithComplexTimeBlocks()
    {
        $place = new Place();
        $place->setStartDate(new \DateTime('25-11-2025'));
        $place->setEndDate(new \DateTime('30-11-2030'));

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
            'Van 25 november 2025 tot en met 30 november 2030'. PHP_EOL
            . '(maandag van 9:30 tot en met 13:45 en van 17:00 tot en met 20:00, '
            . 'dinsdag van 9:30 tot en met 13:45 en van 18:00 tot en met 20:00 en van 21:00 tot en met 23:00, '
            . 'vrijdag van 10:00 tot en met 15:00, '
            . 'zaterdag van 10:00 tot en met 15:00)',
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithoutTimeBlocks()
    {
        $place = new Place();
        $place->setStartDate(new \DateTime('25-11-2025'));
        $place->setEndDate(new \DateTime('30-11-2030'));

        $this->assertEquals(
            'Van 25 november 2025 tot en met 30 november 2030',
            $this->formatter->format($place)
        );
    }
}
