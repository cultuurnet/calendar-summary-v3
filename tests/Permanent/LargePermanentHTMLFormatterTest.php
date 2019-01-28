<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\OpeningHours;
use CultuurNet\SearchV3\ValueObjects\Place;

/**
 * Provide unit tests for large HTML permanent formatter.
 * @package CultuurNet\CalendarSummaryV3\Permanent
 */
class LargePermanentHTMLFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LargePermanentHTMLFormatter
     */
    protected $formatter;

    public function setUp()
    {
        $this->formatter = new LargePermanentHTMLFormatter('nl_NL');
    }

    public function testFormatASimplePermanent()
    {
        $place = new Place();
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
            '<ul class="list-unstyled"> '
            .'<meta itemprop="openingHours" datetime="Ma 0:00-13:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Maandag</span> '
            .'<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">0:00</span> '
            .'<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">13:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Di 0:00-13:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Dinsdag</span> '
            .'<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">0:00</span> '
            .'<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">13:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Wo 0:00-13:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Woensdag</span> '
            .'<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">0:00</span> '
            .'<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">13:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Donderdag"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Donderdag</span> '
            .'<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Vr 9:00-13:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Vrijdag</span> '
            .'<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">9:00</span> '
            .'<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">13:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Za 9:00-19:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Zaterdag</span> '
            .'<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">9:00</span> '
            .'<span itemprop="closes" content="19:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">19:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Zo 9:00-19:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Zondag</span> '
            .'<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">9:00</span> '
            .'<span itemprop="closes" content="19:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">19:00</span> '
            .'</li> </ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAMixedPermanent()
    {
        $place = new Place();

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
            '<ul class="list-unstyled"> '
            .'<meta itemprop="openingHours" datetime="Ma 9:00-20:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Maandag</span> '
            .'<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">9:00</span> '
            .'<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">13:00</span> '
            .'<span itemprop="opens" content="17:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">17:00</span> '
            .'<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">20:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Di 9:00-20:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Dinsdag</span> '
            .'<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">9:00</span> '
            .'<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">13:00</span> '
            .'<span itemprop="opens" content="17:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">17:00</span> '
            .'<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">20:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Wo 9:00-20:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Woensdag</span> '
            .'<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">9:00</span> '
            .'<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">13:00</span> '
            .'<span itemprop="opens" content="17:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">17:00</span> '
            .'<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">20:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Donderdag"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Donderdag</span> '
            .'<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Vr 10:00-21:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Vrijdag</span> '
            .'<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">10:00</span> '
            .'<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">15:00</span> '
            .'<span itemprop="opens" content="18:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">18:00</span> '
            .'<span itemprop="closes" content="21:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">21:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Za 10:00-21:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Zaterdag</span> '
            .'<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">10:00</span> '
            .'<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">15:00</span> '
            .'<span itemprop="opens" content="18:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">18:00</span> '
            .'<span itemprop="closes" content="21:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">21:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Zondag"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Zondag</span> '
            .'<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            .'</li> </ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAComplexPermanent()
    {
        $place = new Place();

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
            '<ul class="list-unstyled"> '
            .'<meta itemprop="openingHours" datetime="Ma 9:30-13:45"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Maandag</span> '
            .'<span itemprop="opens" content="9:30" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">9:30</span> '
            .'<span itemprop="closes" content="13:45" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">13:45</span> '
            .'<span itemprop="opens" content="17:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">17:00</span> '
            .'<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">20:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Di 9:30-13:45"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Dinsdag</span> '
            .'<span itemprop="opens" content="9:30" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">9:30</span> '
            .'<span itemprop="closes" content="13:45" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">13:45</span> '
            .'<span itemprop="opens" content="18:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">18:00</span> '
            .'<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">20:00</span> '
            .'<span itemprop="opens" content="21:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">21:00</span> '
            .'<span itemprop="closes" content="23:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">23:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Woensdag"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Woensdag</span> '
            .'<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Donderdag"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Donderdag</span> '
            .'<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Vr 10:00-15:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Vrijdag</span> '
            .'<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">10:00</span> '
            .'<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">15:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Za 10:00-15:00"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Zaterdag</span> '
            .'<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            .'<span class="cf-time">10:00</span> '
            .'<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot en met</span> '
            .'<span class="cf-time">15:00</span> '
            .'</li> '
            .'<meta itemprop="openingHours" datetime="Zondag"> </meta> '
            .'<li itemprop="openingHoursSpecification"> '
            .'<span class="cf-days">Zondag</span> '
            .'<span itemprop="closed" content="closed" class="cf-closed cf-meta">gesloten</span> '
            .'</li> </ul>',
            $this->formatter->format($place)
        );
    }
}
