<?php

namespace App\Enums;

enum FhirResourceStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';
    case ENTERED_IN_ERROR = 'entered-in-error';
    case COMPLETED = 'completed';
    case IN_PROGRESS = 'in-progress';
    case PLANNED = 'planned';
    case CANCELLED = 'cancelled';
    case STOPPED = 'stopped';
    case ON_HOLD = 'on-hold';
    case UNKNOWN = 'unknown';
    case DRAFT = 'draft';
    case CURRENT = 'current';
    case SUPERSEDED = 'superseded';

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
            self::INACTIVE => 'Inactive',
            self::SUSPENDED => 'Suspended',
            self::ENTERED_IN_ERROR => 'Entered in Error',
            self::COMPLETED => 'Completed',
            self::IN_PROGRESS => 'In Progress',
            self::PLANNED => 'Planned',
            self::CANCELLED => 'Cancelled',
            self::STOPPED => 'Stopped',
            self::ON_HOLD => 'On Hold',
            self::UNKNOWN => 'Unknown',
            self::DRAFT => 'Draft',
            self::CURRENT => 'Current',
            self::SUPERSEDED => 'Superseded',
        };
    }

    /**
     * Get standard facility resource statuses
     *
     * @return array
     */
    public static function facilityStatuses(): array
    {
        return [
            self::ACTIVE->value,
            self::INACTIVE->value,
            self::SUSPENDED->value,
        ];
    }
}
