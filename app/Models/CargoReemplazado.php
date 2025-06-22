<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CargoReemplazado
 * @package App\Models
 * @version October 28, 2019, 8:07 pm -03
 *
 * @property \App\Models\Cargo idcargo
 * @property \App\Models\Puesto idpuesto
 * @property \App\Models\TipoFuncion idtipoFuncion
 * @property \App\Models\TipoNivel idtipoNivel
 * @property \App\Models\TipoAgrupamiento idtipoAgrupamiento
 * @property \App\Models\Agentetitulo idagentetitulo
 * @property \App\Models\TipoEspecialidad idtipoEspecialidad
 * @property \App\Models\TipoCese idtipoCese
 * @property integer idcargo
 * @property boolean resolucion_ministerial
 * @property integer idpuesto
 * @property integer idtipo_funcion
 * @property integer idtipo_nivel
 * @property integer idtipo_agrupamiento
 * @property integer idagentetitulo
 * @property integer idtipo_especialidad
 * @property integer idtipo_cese
 */
class CargoReemplazado extends Model
{
    use SoftDeletes;

    public $table = 'cargo_reemplazado';
    public $primaryKey = 'idcargo_reemplazado';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];



    public $fillable = [
        'idcargo',
        'resolucion_ministerial',
        'idpuesto',
        'idtipo_funcion',
        'idtipo_nivel',
        'idtipo_agrupamiento',
        'idagentetitulo',
        'idtipo_especialidad',
        'idtipo_cese',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idcargo_reemplazado' => 'integer',
        'idcargo' => 'integer',
        'resolucion_ministerial' => 'boolean',
        'idpuesto' => 'integer',
        'idtipo_funcion' => 'integer',
        'idtipo_nivel' => 'integer',
        'idtipo_agrupamiento' => 'integer',
        'idagentetitulo' => 'integer',
        'idtipo_especialidad' => 'integer',
        'idtipo_cese' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'idcargo' => 'required',
        'resolucion_ministerial' => 'required',
        'idpuesto' => 'required',
        'idtipo_funcion' => 'required',
        'idtipo_nivel' => 'required',
        'idtipo_agrupamiento' => 'required',
        'idagentetitulo' => 'required',
        'idtipo_especialidad' => 'required',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function cargo()
    {
        return $this->belongsTo(\App\Models\Cargo::class, 'idcargo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function puesto()
    {
        return $this->belongsTo(\App\Models\Puesto::class, 'idpuesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoFuncion()
    {
        return $this->belongsTo(\App\Models\TipoFuncion::class, 'idtipo_funcion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoNivel()
    {
        return $this->belongsTo(\App\Models\TipoNivel::class, 'idtipo_nivel');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoAgrupamiento()
    {
        return $this->belongsTo(\App\Models\TipoAgrupamiento::class, 'idtipo_agrupamiento');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function agentetitulo()
    {
        return $this->belongsTo(\App\Models\Agentetitulo::class, 'idagentetitulo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoEspecialidad()
    {
        return $this->belongsTo(\App\Models\TipoEspecialidad::class, 'idtipo_especialidad');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoCese()
    {
        return $this->belongsTo(\App\Models\TipoCese::class, 'idtipo_cese');
    }
}
