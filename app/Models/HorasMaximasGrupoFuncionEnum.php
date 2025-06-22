<?php 
namespace App\Models;

enum HorasMaximasGrupoFuncionEnum : int 
{
    case JEFE_RESIDENTES = 180;
    case RESIDENTES_1 = 276;
    case RESIDENTES_2 = 228;

    public static function fromId(int $idtipo_grupo_funcion): static
    {
        return match(true) {
            $idtipo_grupo_funcion === 3 => static::JEFE_RESIDENTES,
            $idtipo_grupo_funcion === 4 => static::RESIDENTES_1,
            $idtipo_grupo_funcion === 7 => static::RESIDENTES_2
        };
    }

    public static function getIdGrupos(): array
    {
        return [3, 4, 7];
    }
}