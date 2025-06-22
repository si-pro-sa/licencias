<?php

namespace App\Enums;

enum FhirEncounterStatus: string
{
    case PLANNED = 'planned';
    case ARRIVED = 'arrived';
    case TRIAGED = 'triaged';
    case IN_PROGRESS = 'in-progress';
    case ONLEAVE = 'onleave';
    case FINISHED = 'finished';
    case CANCELLED = 'cancelled';

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
            self::PLANNED => 'Planned',
            self::ARRIVED => 'Arrived',
            self::TRIAGED => 'Triaged',
            self::IN_PROGRESS => 'In Progress',
            self::ONLEAVE => 'On Leave',
            self::FINISHED => 'Finished',
            self::CANCELLED => 'Cancelled',
        };
    }
}
