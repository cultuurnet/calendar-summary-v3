<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Middleware;

use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\Place;
use CultuurNet\SearchV3\ValueObjects\Status;
use PHPUnit\Framework\TestCase;

class NonAvailablePlaceHTMLFormatterTest extends TestCase
{
    public function testWillInterceptUnavailablePlace(): void
    {
        $place = new Place();
        $place->setStatus(new Status('Unavailable'));

        $formatter = new NonAvailablePlaceHTMLFormatter('nl');
        $result = $formatter->format(
            $place,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('<span class="cf-meta">Permanent gesloten</span>', $result);
    }

    public function testWillInterceptTemporarilyUnavailablePlace(): void
    {
        $place = new Place();
        $place->setStatus(new Status('TemporarilyUnavailable'));

        $formatter = new NonAvailablePlaceHTMLFormatter('nl');
        $result = $formatter->format(
            $place,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('<span class="cf-meta">Tijdelijk gesloten</span>', $result);
    }

    public function testWillIgnoreAvailablePlaces(): void
    {
        $place = new Place();
        $place->setStatus(new Status('Available'));

        $formatter = new NonAvailablePlaceHTMLFormatter('nl');
        $result = $formatter->format(
            $place,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('foo', $result);
    }

    public function testWillIgnoreUnavailableEvents(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Unavailable'));

        $formatter = new NonAvailablePlaceHTMLFormatter('nl');
        $result = $formatter->format(
            $event,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('foo', $result);
    }

    public function testWillIgnoreTemporarilyUnavailableEvents(): void
    {
        $event = new Event();
        $event->setStatus(new Status('TemporarilyUnavailable'));

        $formatter = new NonAvailablePlaceHTMLFormatter('nl');
        $result = $formatter->format(
            $event,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('foo', $result);
    }

    public function testWillIgnoreAvailableEvents(): void
    {
        $event = new Event();
        $event->setStatus(new Status('Unavailable'));

        $formatter = new NonAvailablePlaceHTMLFormatter('nl');
        $result = $formatter->format(
            $event,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('foo', $result);
    }
}
