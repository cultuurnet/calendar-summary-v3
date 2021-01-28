<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3;

use CultuurNet\SearchV3\ValueObjects\Status;

class TranslatedStatusReasonFormatter
{
    /**
     * @var Translator
     */
    private $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function formatAsTitleAttribute(Status $status): string
    {
        $reason = $reason = $status->getReason();

        if (!$reason) {
            return '';
        }

        $translatedReason = $reason->getValueForLanguage($this->translator->getLanguageCode());

        if (empty($translatedReason)) {
            return '';
        }

        return 'title="' . $translatedReason . '" ';
    }
}
