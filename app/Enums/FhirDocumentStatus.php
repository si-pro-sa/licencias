<?php

namespace App\Enums;

enum FhirDocumentStatus: string
{
    case CURRENT = 'current';
    case SUPERSEDED = 'superseded';
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
            self::CURRENT => 'Current',
            self::SUPERSEDED => 'Superseded',
            self::ENTERED_IN_ERROR => 'Entered in Error',
        };
    }
}
