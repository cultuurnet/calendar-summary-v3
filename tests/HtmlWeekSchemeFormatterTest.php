<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Offer\Childcare;
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

    /**
     * Every size that does not ask for the childcare gets a week scheme without it, whether
     * the days share one or not. Both cases reach the childcare through their own branch.
     */
    public function testItRendersNoChildcareUnlessAsked(): void
    {
        $shared = new OpeningHours([
            new OpeningHour(['monday'], '09:00', '16:00', new Childcare('08:00', '17:00')),
            new OpeningHour(['tuesday'], '09:00', '16:00', new Childcare('08:00', '17:00')),
        ]);

        $differing = new OpeningHours([
            new OpeningHour(['monday'], '09:00', '16:00', new Childcare('08:00', '17:00')),
            new OpeningHour(['tuesday'], '09:00', '16:00'),
        ]);

        foreach ([$shared, $differing] as $openingHours) {
            $this->assertStringNotContainsString(
                'cf-childcare',
                HtmlWeekSchemeFormatter::forOpeningHours($openingHours, $this->translator)->toString()
            );
        }
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
