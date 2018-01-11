<?php
/**
 * Created by PhpStorm.
 * User: nicolas
 * Date: 06/03/15
 * Time: 10:54
 */

namespace CultuurNet\CalendarSummaryV3\Single;

use CultuurNet\SearchV3\ValueObjects\Offer;

interface SingleFormatterInterface
{

    /**
     * Format the given offer.
     *
     * @param \CultuurNet\SearchV3\ValueObjects\Offer $offer
     * @return string
     */
    public function format(Offer $offer);
}
