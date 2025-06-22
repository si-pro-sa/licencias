<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Class LdCambioEstado
 * @package App\Models
 * @version March 8, 2020, 8:01 pm -03
 *
 * @property \App\Models\Dependencium idefector
 * @property \App\Models\LdAltum idldAlta
 * @property \App\Models\LdEstado idldEstado
 * @property \App\Models\LdTipoCambioEstado idldTipoCambioEstado
 * @property \App\Models\Periodo idperiodo
 * @property \App\Models\TipoFormulario idtipoFormulario
 * @property \Illuminate\Database\Eloquent\Collection ldAltum1s
 * @property \Illuminate\Database\Eloquent\Collection ldVisadoCambioEstados
 * @property string|\Carbon\Carbon fecha_creado
 * @property string fhasta
 * @property boolean fuera_termino
 * @property boolean usada
 * @property string info_adicional
 * @property integer idefector
 * @property integer idperiodo
 * @property integer idld_estado
 * @property integer idld_tipo_cambio_estado
 * @property integer idld_alta
 * @property integer idtipo_formulario
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 * @property boolean bloqueado
 */
class LdCambioEstado extends Model
{
    public $table = 'ld_cambio_estado';

    protected $primaryKey = 'idld_cambio_estado';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['fecha_creado', 'fhasta'];

    public $fillable = [
        'fecha_creado',
        'fhasta',
        'fuera_termino',
        'usada',
        'info_adicional',
        'idefector',
        'idperiodo',
        'idld_estado',
        'idld_tipo_cambio_estado',
        'idld_alta',
        'idtipo_formulario',
        'usuario',
        'operacion',
        'foperacion',
        'bloqueado'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idld_cambio_estado' => 'integer',
        'fecha_creado' => 'datetime',
        'fhasta' => 'date',
        'fuera_termino' => 'boolean',
        'usada' => 'boolean',
        'info_adicional' => 'string',
        'idefector' => 'integer',
        'idperiodo' => 'integer',
        'idld_estado' => 'integer',
        'idld_tipo_cambio_estado' => 'integer',
        'idld_alta' => 'integer',
        'idtipo_formulario' => 'integer',
        'usuario' => 'string',
        'operacion' => 'string',
        'foperacion' => 'datetime',
        'bloqueado' => 'boolean'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fecha_creado' => 'required',
        'fhasta' => 'required',
        'fuera_termino' => 'required',
        'usada' => 'required',
        'idefector' => 'required',
        'idperiodo' => 'required',
        'idld_estado' => 'required',
        'idld_tipo_cambio_estado' => 'required',
        'idld_alta' => 'required',
        'idtipo_formulario' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function efector()
    {
        return $this->belongsTo(\App\Models\Dependencium::class, 'idefector');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ldAlta()
    {
        return $this->belongsTo(\App\Models\LdAltum::class, 'idld_alta');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ldEstado()
    {
        return $this->belongsTo(\App\Models\LdEstado::class, 'idld_estado');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function ldTipoCambioEstado()
    {
        return $this->belongsTo(\App\Models\LdTipoCambioEstado::class, 'idld_tipo_cambio_estado');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function periodo()
    {
        return $this->belongsTo(\App\Models\Periodo::class, 'idperiodo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function tipoFormulario()
    {
        return $this->belongsTo(\App\Models\TipoFormulario::class, 'idtipo_formulario');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function ldAltum1s()
    {
        return $this->belongsToMany(\App\Models\LdAltum::class, 'ld_alta_baja_relacion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ldVisadoCambioEstados()
    {
        return $this->hasMany(\App\Models\LdVisadoCambioEstado::class, 'idld_cambio_estado');
    }
}
