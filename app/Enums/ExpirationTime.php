<?php

namespace App\Enums;

enum ExpirationTime: int
{
    case THIRTY_MINUTES = 30;
    case ONE_HOUR = 60;
    case SIX_HOURS = 360;
    case TWELVE_HOURS = 720;
    case ONE_DAY = 1440;

    public function label(): string
    {
        return match ($this) {
            self::THIRTY_MINUTES => '30 Minutes',
            self::ONE_HOUR => '1 Hour',
            self::SIX_HOURS => '6 Hours',
            self::TWELVE_HOURS => '12 Hours',
            self::ONE_DAY => '24 Hours',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
