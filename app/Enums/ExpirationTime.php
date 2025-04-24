<?php

namespace App\Enums;

enum ExpirationTime: int
{
    case THIRTY_MINUTES = 30;
    case ONE_HOUR = 60;
    case SIX_HOURS = 360;
    case TWELVE_HOURS = 720;
    case ONE_DAY = 1440;

    public function text(): string
    {
        return match ($this) {
            self::THIRTY_MINUTES => '30 Minutes',
            self::ONE_HOUR => '1 Hour',
            self::SIX_HOURS => '6 Hours',
            self::TWELVE_HOURS => '12 Hours',
            self::ONE_DAY => '24 Hours',
        };
    }

    /**
     * @return array<int, array<string, int|string>>
     */
    public static function label(): array
    {
        return [
            [
                'value' => self::THIRTY_MINUTES->value,
                'label' => '30 Minutes',
            ],
            [
                'value' => self::ONE_HOUR->value,
                'label' => '1 Hour',
            ],
            [
                'value' => self::SIX_HOURS->value,
                'label' => '6 Hours',
            ],
            [
                'value' => self::TWELVE_HOURS->value,
                'label' => '12 Hours',
            ],
            [
                'value' => self::ONE_DAY->value,
                'label' => '24 Hours',
            ],
        ];
    }
}
