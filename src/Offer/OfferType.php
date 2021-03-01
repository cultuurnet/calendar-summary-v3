<?php

declare(strict_types=1);

namespace CultuurNet\CalendarSummaryV3\Offer;

use InvalidArgumentException;

class OfferType
{
    private const EVENT = 'event';
    private const PLACE = 'place';

    private const ALLOWED_VALUES = [
        self::EVENT,
        self::PLACE,
    ];

    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::ALLOWED_VALUES)) {
            throw new InvalidArgumentException('Invalid CalendarType: ' . $value);
        }
        $this->value = $value;
    }

    public static function event(): self
    {
        return new self(self::EVENT);
    }

    public static function place(): self
    {
        return new self(self::PLACE);
    }

    public function equals(OfferType $other): bool
    {
        return $this->value === $other->value;
    }
}
