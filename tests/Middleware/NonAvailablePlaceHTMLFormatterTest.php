<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Middleware;

use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Event;
use CultuurNet\SearchV3\ValueObjects\Place;
use CultuurNet\SearchV3\ValueObjects\Status;
use CultuurNet\SearchV3\ValueObjects\TranslatedString;
use PHPUnit\Framework\TestCase;

class NonAvailablePlaceHTMLFormatterTest extends TestCase
{
    /**
     * @var NonAvailablePlaceHTMLFormatter
     */
    private $formatter;

    protected function setUp(): void
    {
        $translator = new Translator('nl_BE');
        $this->formatter = new NonAvailablePlaceHTMLFormatter($translator);
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

        $this->assertEquals('<span class="cf-status">Permanent gesloten</span>', $result);
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

        $this->assertEquals('<span class="cf-status">Tijdelijk gesloten</span>', $result);
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

    public function testItWillAddTitleAttributeWithReason(): void
    {
        $place = new Place();
        $place->setStatus(new Status('Unavailable', new TranslatedString(['nl' => 'Covid-19'])));

        $result = $this->formatter->format(
            $place,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('<span title="Covid-19" class="cf-status">Permanent gesloten</span>', $result);
    }

    public function testItWillNotAddTitleAttributeWhenReasonIsNotAvailableInCorrectLanguage(): void
    {
        $place = new Place();
        $place->setStatus(new Status('Unavailable', new TranslatedString(['fr' => "Désolé, c'est annulé!"])));

        $result = $this->formatter->format(
            $place,
            function () {
                return 'foo';
            }
        );

        $this->assertEquals('<span class="cf-status">Permanent gesloten</span>', $result);
    }
}
