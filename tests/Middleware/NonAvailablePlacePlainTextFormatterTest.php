<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Middleware;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\Place;
use CultuurNet\SearchV3\ValueObjects\Status;
use PHPUnit\Framework\TestCase;

final class NonAvailablePlacePlainTextFormatterTest extends TestCase
{
    /**
     * @var NonAvailablePlacePlainTextFormatter
     */
    private $formatter;

    protected function setUp(): void
    {
        $translator = new Translator('nl_BE');
        $this->formatter = new NonAvailablePlacePlainTextFormatter($translator);
    }

    public function testWillInterceptUnavailablePlace(): void
    {
        $place = new Place();
        $place->setStatus(new Status('Unavailable'));

        $result = $this->formatter->format(
            $place,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('Permanent gesloten', $result);
    }

    public function testWillInterceptTemporarilyUnavailablePlace(): void
    {
        $place = new Place();
        $place->setStatus(new Status('TemporarilyUnavailable'));

        $result = $this->formatter->format(
            $place,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('Tijdelijk gesloten', $result);
    }

    public function testWillIgnoreAvailablePlaces(): void
    {
        $place = new Place();
        $place->setStatus(new Status('Available'));

        $result = $this->formatter->format(
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

        $result = $this->formatter->format(
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

        $result = $this->formatter->format(
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

        $result = $this->formatter->format(
            $event,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('foo', $result);
    }
}
