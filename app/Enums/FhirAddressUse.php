<?php

namespace App\Enums;

enum FhirAddressUse: string
{
    case HOME = 'home';
    case WORK = 'work';
    case TEMP = 'temp';
    case OLD = 'old';
    case BILLING = 'billing';

    /**
     * Get all available values as an array
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get a human-readable label for the enum
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::HOME => 'Home',
            self::WORK => 'Work',
            self::TEMP => 'Temporary',
            self::OLD => 'Old/Previous',
            self::BILLING => 'Billing',
        };
    }
}
