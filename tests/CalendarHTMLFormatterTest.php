<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\AdjustedDay;
use CultuurNet\CalendarSummaryV3\Offer\BookingAvailability;
use CultuurNet\CalendarSummaryV3\Offer\CalendarType;
use CultuurNet\CalendarSummaryV3\Offer\ClosedDay;
use CultuurNet\CalendarSummaryV3\Offer\Offer;
use CultuurNet\CalendarSummaryV3\Offer\OfferType;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHours;
use CultuurNet\CalendarSummaryV3\Offer\Status;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class CalendarHTMLFormatterTest extends TestCase
{
    /**
     * @var CalendarHTMLFormatter
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new CalendarHTMLFormatter();
    }

    public function testGeneralFormatMethod(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00'),
            CalendarType::single()
        );

        $this->assertSame(
            '<span class="cf-weekday cf-meta">Do</span> <span class="cf-date">25</span> <span class="cf-month">jan</span> <span class="cf-year">2018</span>',
            $this->formatter->format($offer, 'xs')
        );
    }

    public function testExtraLargeFormatRendersTheAdjustedAndClosedDays(): void
    {
        $place = (new Offer(
            OfferType::place(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            null,
            null,
            CalendarType::permanent()
        ))->withAdjustedDays(
            [
                new AdjustedDay(
                    new DateTimeImmutable('2030-11-02'),
                    new DateTimeImmutable('2030-11-02'),
                    new OpeningHours(),
                    ['nl' => 'Herfstvakantie']
                ),
            ]
        )->withClosedDays(
            [
                new ClosedDay(
                    new DateTimeImmutable('2030-12-25'),
                    new DateTimeImmutable('2030-12-25'),
                    ['nl' => 'Kerstmis']
                ),
            ]
        );

        $this->assertSame(
            '<p class="cf-openinghours">Alle dagen open</p> '
            . '<details class="cf-adjusted-days"> '
            . '<summary>Behalve tijdens</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Zaterdag 2 november 2030</span> '
            . '<span class="cf-description">Herfstvakantie</span> '
            . '</li> </ul> </details> '
            . '<details class="cf-closed-days"> '
            . '<summary>Gesloten</summary> '
            . '<ul class="list-unstyled"> '
            . '<li> '
            . '<span class="cf-date">Woensdag 25 december 2030</span> '
            . '<span class="cf-description">Kerstmis</span> '
            . '</li> </ul> </details>',
            $this->formatter->format($place, 'xl')
        );

        // The large format does not know about adjusted or closed days.
        $this->assertSame(
            '<p class="cf-openinghours">Alle dagen open</p>',
            $this->formatter->format($place, 'lg')
        );
    }

    public function testGeneralFormatMethodAndCatchException(): void
    {
        $offer = new Offer(
            OfferType::event(),
            new Status('Available', []),
            new BookingAvailability('Available'),
            new DateTimeImmutable('2018-01-25T20:00:00+01:00'),
            new DateTimeImmutable('2018-01-25T21:30:00+01:00'),
            CalendarType::single()
        );

        $this->expectException(FormatterException::class);
        $this->formatter->format($offer, 'sx');
    }
}
