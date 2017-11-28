<?php

namespace CultuurNet\CalendarSummaryV3\Permanent;

use CultuurNet\SearchV3\ValueObjects\Place;

/**
 * Provide a large plain text formatter for permanent calendar type.
 * @package CultuurNet\CalendarSummaryV3\Permanent
 */
class LargePermanentPlainTextFormatter implements PermanentFormatterInterface
{
    /**
     * Translate the day in short Dutch.
     */
    protected $mappingDays = array(
        'monday' => 'Ma',
        'tuesday' => 'Di',
        'wednesday' => 'Wo',
        'thursday' => 'Do',
        'friday' => 'Vr',
        'saturday' => 'Za',
        'sunday' => 'Zo',
    );

    /**
     * Return formatted permanent string.
     *
     * @param Place $place
     * @return string
     */
    public function format(Place $place)
    {
        $output = '';
        if ($place->getOpeningHours()) {
            $output .= $this->generateWeekScheme($place->getOpeningHours());
        }
        return $output;
    }

    /**
     * @param $time
     * @return string
     */
    protected function getFormattedTime($time)
    {
        $formattedShortTime = ltrim($time, '0');
        if ($formattedShortTime == ':00') {
            $formattedShortTime = '0:00';
        }
        return $formattedShortTime;
    }

    /**
     * @param $openingHoursData
     * @return string
     */
    protected function generateWeekScheme($openingHoursData)
    {
        $outputWeek = '';
        // Create an array with formatted days.
        $formattedDays = [];
        foreach ($openingHoursData as $openingHours) {
            foreach ($openingHours->getDayOfWeek() as $dayOfWeek) {
                if (!isset($formattedDays[$dayOfWeek])) {
                    $formattedDays[$dayOfWeek] = $this->mappingDays[$dayOfWeek]
                        . ' Van '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' tot ' . $this->getFormattedTime($openingHours->getCloses())
                        . PHP_EOL;
                } else {
                    $formattedDays[$dayOfWeek] .= 'Van '
                        . $this->getFormattedTime($openingHours->getOpens())
                        . ' tot ' . $this->getFormattedTime($openingHours->getCloses())
                        . PHP_EOL;
                }
            }
        }
        // Create an array with formatted closed days.
        $closedDays = [];
        foreach (array_keys($this->mappingDays) as $day) {
            $closedDays[$day] = $this->mappingDays[$day] . '  gesloten' . PHP_EOL;
        }
        // Merge the formatted days with the closed days array to fill in missing days and sort using the days mapping.
        $formattedDays = array_replace($this->mappingDays, $formattedDays + $closedDays);
        // Render the rest of the week scheme output.
        foreach ($formattedDays as $formattedDay) {
            $outputWeek .= $formattedDay;
        }
        return $outputWeek;
    }
}
