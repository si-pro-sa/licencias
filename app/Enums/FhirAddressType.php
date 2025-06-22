<?php

namespace App\Enums;

enum FhirAddressType: string
{
    case POSTAL = 'postal';
    case PHYSICAL = 'physical';
    case BOTH = 'both';

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
            self::POSTAL => 'Postal',
            self::PHYSICAL => 'Physical',
            self::BOTH => 'Both',
        };
    }
}
