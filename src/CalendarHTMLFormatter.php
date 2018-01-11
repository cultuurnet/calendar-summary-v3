<?php

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\CalendarSummaryV3\Multiple\MediumMultipleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Single\LargeSingleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Single\MediumSingleHTMLFormatter;
use CultuurNet\CalendarSummaryV3\Single\SmallSingleHTMLFormatter;
use CultuurNet\SearchV3\ValueObjects\Offer;
use CultuurNet\CalendarSummaryV3\Permanent\LargePermanentHTMLFormatter;

/**
 * Provides a formatter for calendar info of offers.
 */
class CalendarHTMLFormatter implements CalendarFormatterInterface
{
    protected $mapping = array();

    public function __construct()
    {
        $this->mapping = [
            Offer::CALENDAR_TYPE_SINGLE =>
                [
                    'lg' => new LargeSingleHTMLFormatter(),
                    'md' => new MediumSingleHTMLFormatter(),
                    'sm' => new SmallSingleHTMLFormatter(),
                    'xs' => new SmallSingleHTMLFormatter()
                ],
            Offer::CALENDAR_TYPE_MULTIPLE =>
                [
                    //'lg' => new LargeTimestampsHTMLFormatter(),
                    'md' => new MediumMultipleHTMLFormatter(),
                ],
            Offer::CALENDAR_TYPE_PERIODIC =>
                [
                    //'lg' => new LargePeriodHTMLFormatter(),
                    //'md' => new MediumPeriodHTMLFormatter(),
                    //'sm' => new SmallPeriodHTMLFormatter(),
                    //'xs' => new ExtraSmallPeriodicHTMLFormatter(),
                ],
            Offer::CALENDAR_TYPE_PERMANENT =>
                [
                    'lg' => new LargePermanentHTMLFormatter(),
                ],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @throws FormatterException
     */
    public function format(Offer $offer, $format)
    {
        $calenderType = $offer->getCalendarType();

        if (isset($this->mapping[$calenderType][$format])) {
            $formatter = $this->mapping[$calenderType][$format];
        } else {
            throw new FormatterException($format . ' format not supported for ' . $calenderType);
        }

        return $formatter->format($offer);
    }
}
