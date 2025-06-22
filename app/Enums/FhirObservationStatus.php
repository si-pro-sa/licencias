<?php

namespace App\Enums;

enum FhirObservationStatus: string
{
    case REGISTERED = 'registered';
    case PRELIMINARY = 'preliminary';
    case FINAL = 'final';
    case AMENDED = 'amended';
    case CORRECTED = 'corrected';
    case CANCELLED = 'cancelled';
    case ENTERED_IN_ERROR = 'entered-in-error';
    case UNKNOWN = 'unknown';

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
            self::REGISTERED => 'Registered',
            self::PRELIMINARY => 'Preliminary',
            self::FINAL => 'Final',
            self::AMENDED => 'Amended',
            self::CORRECTED => 'Corrected',
            self::CANCELLED => 'Cancelled',
            self::ENTERED_IN_ERROR => 'Entered in Error',
            self::UNKNOWN => 'Unknown',
        };
    }
}
