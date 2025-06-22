<?php

namespace App\Enums;

enum FhirNoteType: string
{
    case PHYSICIAN = '34839-1';
    case NURSING = '34746-8';
    case PROGRESS = '11506-3';
    case CONSULT = '11488-4';
    case DISCHARGE = '18842-5';
    case PROCEDURE = '28570-0';

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
            self::PHYSICIAN => 'Physician Note',
            self::NURSING => 'Nursing Note',
            self::PROGRESS => 'Progress Note',
            self::CONSULT => 'Consultation Note',
            self::DISCHARGE => 'Discharge Summary',
            self::PROCEDURE => 'Procedure Note',
        };
    }

    /**
     * Get the LOINC code description
     *
     * @return string
     */
    public function loincDisplay(): string
    {
        return match ($this) {
            self::PHYSICIAN => 'Physician note',
            self::NURSING => 'Nursing note',
            self::PROGRESS => 'Progress note',
            self::CONSULT => 'Consultation note',
            self::DISCHARGE => 'Discharge summary',
            self::PROCEDURE => 'Procedure note',
        };
    }
}
