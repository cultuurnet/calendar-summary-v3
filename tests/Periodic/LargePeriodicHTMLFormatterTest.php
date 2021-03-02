<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Periodic;

use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use CultuurNet\CalendarSummaryV3\Translator;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class LargePeriodicHTMLFormatterTest extends TestCase
{
    /**
     * @var LargePeriodicHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new LargePeriodicHTMLFormatter(new Translator('nl_NL'));
    }

    public function testFormatAPeriodWithSingleTimeBlocks(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $place = $place->withOpeningHours(
            [
                new OpeningHour(
                    ['monday','tuesday', 'wednesday'],
                    '00:01',
                    '17:00'
                ),
                new OpeningHour(
                    ['friday', 'saturday'],
                    '10:00',
                    '18:00'
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-period"> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p> '
            . '<p class="cf-openinghours">Open op:</p> '
            . '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 0:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 0:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Wo 0:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Woensdag</span> '
            . '<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Vr 10:00-18:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Vrijdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="18:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">18:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Za 10:00-18:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zaterdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="18:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">18:00</span> '
            . '</li> </ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithSingleTimeBlocksWithUnavailableStatus(): void
    {
        $event = new Offer(
            OfferType::event(),
            new Status('Unavailable', []),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $event = $event->withOpeningHours(
            [
                new OpeningHour(
                    ['monday','tuesday', 'wednesday'],
                    '00:01',
                    '17:00'
                ),
                new OpeningHour(
                    ['friday', 'saturday'],
                    '10:00',
                    '18:00'
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-period"> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p> '
            . '<p class="cf-openinghours">Open op:</p> '
            . '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 0:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 0:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Wo 0:00-17:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Woensdag</span> '
            . '<span itemprop="opens" content="0:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">0:00</span> '
            . '<span itemprop="closes" content="17:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">17:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Vr 10:00-18:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Vrijdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="18:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">18:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Za 10:00-18:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zaterdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="18:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">18:00</span> '
            . '</li> </ul>'
            . ' '
            . '<span class="cf-status">(geannuleerd)</span>',
            $this->formatter->format($event)
        );
    }

    public function testFormatAPeriodWithSplitTimeBlocks(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $place = $place->withOpeningHours(
            [
                new OpeningHour(
                    ['monday','tuesday', 'wednesday'],
                    '09:00',
                    '13:00'
                ),
                new OpeningHour(
                    ['monday','tuesday', 'wednesday'],
                    '17:00',
                    '20:00'
                ),
                new OpeningHour(
                    ['friday', 'saturday'],
                    '10:00',
                    '15:00'
                ),
                new OpeningHour(
                    ['friday', 'saturday'],
                    '18:00',
                    '21:00'
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-period"> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p> '
            . '<p class="cf-openinghours">Open op:</p> '
            . '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 9:00-20:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">en van</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 9:00-20:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">en van</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Wo 9:00-20:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Woensdag</span> '
            . '<span itemprop="opens" content="9:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:00</span> '
            . '<span itemprop="closes" content="13:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:00</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">en van</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Vr 10:00-21:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Vrijdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">15:00</span> '
            . '<span itemprop="opens" content="18:00" class="cf-from cf-meta">en van</span> '
            . '<span class="cf-time">18:00</span> '
            . '<span itemprop="closes" content="21:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">21:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Za 10:00-21:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zaterdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">15:00</span> '
            . '<span itemprop="opens" content="18:00" class="cf-from cf-meta">en van</span> '
            . '<span class="cf-time">18:00</span> '
            . '<span itemprop="closes" content="21:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">21:00</span> '
            . '</li> </ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithComplexTimeBlocks(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $place = $place->withOpeningHours(
            [
                new OpeningHour(
                    ['monday','tuesday'],
                    '09:30',
                    '13:45'
                ),
                new OpeningHour(
                    ['monday'],
                    '17:00',
                    '20:00'
                ),
                new OpeningHour(
                    ['tuesday'],
                    '18:00',
                    '20:00'
                ),
                new OpeningHour(
                    ['tuesday'],
                    '21:00',
                    '23:00'
                ),
                new OpeningHour(
                    ['friday', 'saturday'],
                    '10:00',
                    '15:00'
                ),
            ]
        );

        $this->assertEquals(
            '<p class="cf-period"> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p> '
            . '<p class="cf-openinghours">Open op:</p> '
            . '<ul class="list-unstyled"> '
            . '<meta itemprop="openingHours" datetime="Ma 9:30-13:45"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Maandag</span> '
            . '<span itemprop="opens" content="9:30" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:30</span> '
            . '<span itemprop="closes" content="13:45" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:45</span> '
            . '<span itemprop="opens" content="17:00" class="cf-from cf-meta">en van</span> '
            . '<span class="cf-time">17:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Di 9:30-13:45"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Dinsdag</span> '
            . '<span itemprop="opens" content="9:30" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">9:30</span> '
            . '<span itemprop="closes" content="13:45" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">13:45</span> '
            . '<span itemprop="opens" content="0:00" class="cf-from cf-meta">en van</span> '
            . '<span class="cf-time">0:00</span> '
            . '<span itemprop="closes" content="20:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">20:00</span> '
            . '<span itemprop="opens" content="0:01" class="cf-from cf-meta">en van</span> '
            . '<span class="cf-time">0:01</span> '
            . '<span itemprop="closes" content="0:59" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">0:59</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Vr 10:00-15:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Vrijdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">15:00</span> '
            . '</li> '
            . '<meta itemprop="openingHours" datetime="Za 10:00-15:00"> </meta> '
            . '<li itemprop="openingHoursSpecification"> '
            . '<span class="cf-days">Zaterdag</span> '
            . '<span itemprop="opens" content="10:00" class="cf-from cf-meta">van</span> '
            . '<span class="cf-time">10:00</span> '
            . '<span itemprop="closes" content="15:00" class="cf-to cf-meta">tot</span> '
            . '<span class="cf-time">15:00</span> '
            . '</li> </ul>',
            $this->formatter->format($place)
        );
    }

    public function testFormatAPeriodWithoutTimeBlocks(): void
    {
        $place = new Offer(
            OfferType::place(),
            new Status('Available', []),
            new DateTimeImmutable('25-11-2025'),
            new DateTimeImmutable('30-11-2030'),
            CalendarType::periodic()
        );

        $this->assertEquals(
            '<p class="cf-period"> '
            . '<time itemprop="startDate" datetime="2025-11-25"> '
            . '<span class="cf-date">25 november 2025</span> '
            . '</time> '
            . '<span class="cf-to cf-meta">tot</span> '
            . '<time itemprop="endDate" datetime="2030-11-30"> '
            . '<span class="cf-date">30 november 2030</span> '
            . '</time> '
            . '</p>',
            $this->formatter->format($place)
        );
    }
}
