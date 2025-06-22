<?php

namespace App\Enums;

enum FhirNoteStatus: string
{
    case PRELIMINARY = 'preliminary';
    case FINAL = 'final';
    case AMENDED = 'amended';
    case ENTERED_IN_ERROR = 'entered-in-error';

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
            self::PRELIMINARY => 'Draft',
            self::FINAL => 'Final',
            self::AMENDED => 'Amended',
            self::ENTERED_IN_ERROR => 'Entered in Error',
        };
    }
}
