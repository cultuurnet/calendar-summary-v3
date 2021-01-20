<?php

namespace CultuurNet\CalendarSummaryV3\Multiple;

use CultuurNet\CalendarSummaryV3\OfferFormatter;
use CultuurNet\CalendarSummaryV3\Translator;
use CultuurNet\SearchV3\ValueObjects\Offer;
use DateTimeZone;

final class ExtraSmallEventPlainTextFormatter implements OfferFormatter
{
    /**
     * @var Translator
     */
    private $trans;

    public function __construct(string $langCode)
    {
        $this->trans = new Translator();
        $this->trans->setLanguage($langCode);
    }

    public function format(Offer $offer): string
    {
        $dateFrom = $offer->getStartDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $dateTo = $offer->getEndDate()->setTimezone(new DateTimeZone(date_default_timezone_get()));

        if ($dateFrom == $dateTo) {
            return $dateFrom->format('j/n/y');
        }

        return ucfirst($this->trans->getTranslations()->t('from')) . ' ' .
            $dateFrom->format('j/n/y') . ' ' .
            $this->trans->getTranslations()->t('till') . ' '.
            $dateTo->format('j/n/y');
    }
}
