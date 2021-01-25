<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Middleware;

use CultuurNet\CalendarSummaryV3\NonAvailablePlaceFormatter;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\Place;
use CultuurNet\SearchV3\ValueObjects\Status;
use PHPUnit\Framework\TestCase;

class NonAvailablePlaceFormatterTest extends TestCase
{
    public function testWillInterceptUnavailablePlace(): void
    {
        $place = new Place();
        $place->setStatus(new Status('Unavailable'));

        $formatter = new NonAvailablePlaceFormatter('nl');
        $result = $formatter->format(
            $place,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('permanent gesloten', $result);
    }

    public function testWillInterceptTemporarilyUnavailablePlace(): void
    {
        $place = new Place();
        $place->setStatus(new Status('TemporarilyUnavailable'));

        $formatter = new NonAvailablePlaceFormatter('nl');
        $result = $formatter->format(
            $place,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('tijdelijk gesloten', $result);
    }

    public function testWillIgnoreAvailablePlaces(): void
    {
        $place = new Place();
        $place->setStatus(new Status('Available'));

        $formatter = new NonAvailablePlaceFormatter('nl');
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

        $formatter = new NonAvailablePlaceFormatter('nl');
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

        $formatter = new NonAvailablePlaceFormatter('nl');
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

        $formatter = new NonAvailablePlaceFormatter('nl');
        $result = $formatter->format(
            $event,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('foo', $result);
    }
}
