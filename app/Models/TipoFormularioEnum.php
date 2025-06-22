<?php 
namespace App\Models;

enum TipoFormularioEnum 
{
    case GUARDIA;
    case LIBRE_DISPONIBILIDAD;
    case REEMPLAZO;
    case CARGO;
    case OTRO;

    public static function fromString(string $tipo): static
    {
        return match(true) {
            $tipo === 'GUARDIA' => static::GUARDIA,
            $tipo === 'LIBRE_DISPONIBILIDAD' => static::LIBRE_DISPONIBILIDAD,
            $tipo === 'REEMPLAZO' => static::REEMPLAZO,
            $tipo === 'CARGO' => static::CARGO,
            default => static::OTRO,
        };
    }

    public function text(): string {
        return match($this) {
            self::GUARDIA => 'Guardia',
            self::LIBRE_DISPONIBILIDAD => 'Libre Disponibilidad',
            self::REEMPLAZO => 'Reemplazo',
            self::CARGO => 'Cargo',
            default => 'Otro'
        };
    }
}