<?php

namespace App\Models;

use DateTime;
use Carbon\Carbon;
use App\MasterModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\belongsToMany;

class VAgentePlanta extends MasterModel
{
    public $table = 'v_agente_planta';
    protected $primaryKey = 'idpuesto';
    protected $connection = 'pgsql_public';

    public $fillable = [
        'apellido',
        'nombre',
        'documento',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idpuesto' => 'integer',
        'idagente' => 'integer',
        'iddependencia' => 'integer',
        'documento' => 'integer',
        'apellido' => 'string',
        'nombre' => 'string',
        'grupo_funcion' => 'string',
        'funcion' => 'string',
        'planta' => 'string',
        'efector' => 'string',
        'servicio' => 'string',
        'hora_desde' => 'string',
        'hora_hasta' => 'string',
        'tipodia' => 'string',
        'cantidad_horas' => 'float',
        'horario_nuevo' => 'string',
        'area_operativa' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
    ];

    /**
     * Validation rules
     *
     * @var array
     */

    public function setApellidoAttribute($value)
    {
        $this->attributes['apellido'] = strtoupper($value);
    }

    public function setNombreAttribute($value)
    {
        $this->attributes['nombre'] = strtoupper($value);
    }

}