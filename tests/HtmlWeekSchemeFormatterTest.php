<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\OpeningHour;
use CultuurNet\CalendarSummaryV3\Offer\OpeningHours;
use PHPUnit\Framework\TestCase;

final class HtmlWeekSchemeFormatterTest extends TestCase
{
    private Translator $translator;

    protected function setUp(): void
    {
        date_default_timezone_set('Europe/Brussels');
        $this->translator = new Translator('nl_NL');
    }

    public function testItRendersNothingWithoutOpeningHours(): void
    {
        $this->assertSame(
            '',
            HtmlWeekSchemeFormatter::forOpeningHours(new OpeningHours(), $this->translator)
                ->toString()
        );
    }

    public function testItRendersNoHeadingOrClosedDaysWithoutOpeningHours(): void
    {
        $this->assertSame(
            '',
            HtmlWeekSchemeFormatter::forOpeningHours(new OpeningHours(), $this->translator)
                ->withHeading()
                ->withEveryDayOfTheWeek()
                ->toString()
        );
    }

    public function testItStillMarksTheDaysWithoutOpeningHoursAsClosed(): void
    {
        $weekScheme = HtmlWeekSchemeFormatter::forOpeningHours(
            new OpeningHours([new OpeningHour(['monday'], '10:00', '18:00')]),
            $this->translator
        )->withEveryDayOfTheWeek()->toString();

        // Only an entirely empty week scheme is left out, a partially filled one
        // keeps reporting the days that are closed.
        $this->assertStringContainsString('Maandag', $weekScheme);
        $this->assertStringContainsString('cf-closed', $weekScheme);
    }
}
