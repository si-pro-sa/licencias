<?php
namespace App\Models;

use Eloquent as Model;
use App\Models\GuardiaCodigoCosto;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* Class TipoAgrupamiento
* @package App\Models
* @version March 30, 2022, 7:47 pm UTC
*
*/
class GuardiaCodigo extends Model
{
    use SoftDeletes;
    public $table = 'guardia_codigo';
    protected $primaryKey = 'idguardia_codigo';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    /**
    * The attributes that should be casted to native types.
    *
    * @var array
    */
    protected $casts = [
        'idguardia_codigo' => 'integer',
        'codigo' => 'string',
        'nombre' => 'string',
        'dias' => 'integer',
        'horas' => 'integer',
        'idtipo_nivel' => 'integer',
    ];

    /**
    * Validation rules
    *
    * @var array
    */
    public static $rules = [

    ];

    /**
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    **/
    public function tipoNivel()
    {
        return $this->belongsTo(\App\Models\TipoNivel::class);
    }

    /**
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    **/
    public function guardiaCodigoCosto()
    {
        return $this->belongsTo(\App\Models\GuardiaCodigoCosto::class);
    }

    /**
     * Busca el último importe de Guardias
     */
    public function getImporte(string $fecha): string
    {
        $costo = GuardiaCodigoCosto::select('monto')
                                    ->where('idguardia_codigo', $this->idguardia_codigo)
                                    ->where('fdesde', '<=', $fecha)
                                    ->orderBy('fdesde', 'desc')
                                    ->first();

        return (string) $costo->monto ?? '0';
    }

    /**
     * Obtengo monto y código según parámetros
     */
    public static function getMontoCodigo(float $cantidad, int $idtipo_nivel, int $idtipo_guardia, int $dias, $fecha): array
    {
        $codigo = GuardiaCodigo::select('idguardia_codigo', 'codigo')
                                ->where('idtipo_nivel', $idtipo_nivel)
                                ->where('idtipo_guardia', $idtipo_guardia)
                                ->where('dias', $dias)
                                ->first();
        $montoCodigo['nombre'] = isset($codigo->codigo) ? str_pad($codigo->codigo, 6, '0', STR_PAD_LEFT) : '';
        $importe = isset($codigo) ? $codigo->getImporte($fecha) : 0;
        $montoCodigo['total'] = number_format(round($importe * $cantidad, 2), 2, ',', '.');

        return $montoCodigo;
    }
}
