<?php

namespace App\Enums;

enum FhirConditionStatus: string
{
    case ACTIVE = 'active';
    case RECURRENCE = 'recurrence';
    case RELAPSE = 'relapse';
    case INACTIVE = 'inactive';
    case REMISSION = 'remission';
    case RESOLVED = 'resolved';

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
            self::ACTIVE => 'Active',
            self::RECURRENCE => 'Recurrence',
            self::RELAPSE => 'Relapse',
            self::INACTIVE => 'Inactive',
            self::REMISSION => 'Remission',
            self::RESOLVED => 'Resolved',
        };
    }
}
