<?php

namespace App\Enums;

enum MaxUses: int
{
    case ONE = 1;
    case TW0 = 2;
    case THREE = 3;
    case FOUR = 4;
    case FIVE = 5;

    public function label(): string
    {
        return (string) $this->value;
    }

    /**
     * @return array<int, int>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
