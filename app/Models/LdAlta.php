<?php

namespace App\Models;

use Eloquent as Model;

/**
 * Altas de Libre Disponibilidad
 * Class LdAlta
 * @package App\Models
 * @version March 8, 2020, 7:56 pm -03
 *
 * @property \App\Models\Dependencium dependenciaDestino
 * @property \App\Models\Dependencium dependenciaOrigen
 * @property \App\Models\Dependencium efector
 * @property \App\Models\LdCodigo ldCodigo
 * @property \App\Models\LdEstado ldEstado
 * @property \App\Models\LdTipoAltum ldTipoAlta
 * @property \App\Models\Periodo periodo
 * @property \App\Models\Puesto puesto
 * @property \App\Models\TipoAgrupamiento tipoAgrupamiento
 * @property \App\Models\TipoFormulario tipoFormulario
 * @property \Illuminate\Database\Eloquent\Collection ldCambioEstados
 * @property \Illuminate\Database\Eloquent\Collection ldCambioEstado3s
 * @property \Illuminate\Database\Eloquent\Collection ldAltaRelacions
 * @property \Illuminate\Database\Eloquent\Collection ldAltaRelacion4s
 * @property \Illuminate\Database\Eloquent\Collection ldVisadoAlta
 * @property string|\Carbon\Carbon fecha_creado
 * @property string fdesde
 * @property string fhasta
 * @property boolean fuera_termino
 * @property number valor
 * @property string info_adicional
 * @property boolean bloqueado
 * @property integer idtipo_formulario
 * @property integer idld_estado
 * @property integer idld_tipo_alta
 * @property integer idpuesto
 * @property integer iddependencia_origen
 * @property integer iddependencia_destino
 * @property integer idefector
 * @property integer idld_codigo
 * @property integer idperiodo
 * @property integer idtipo_agrupamiento
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 */
class LdAlta extends Model
{
    public $table = 'ld_alta';

    protected $primaryKey = 'idld_alta';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['fdesde', 'fhasta'];

    public $fillable = [
        'fecha_creado',
        'fdesde',
        'fhasta',
        'fuera_termino',
        'valor',
        'info_adicional',
        'bloqueado',
        'idtipo_formulario',
        'idld_estado',
        'idld_tipo_alta',
        'idpuesto',
        'iddependencia_origen',
        'iddependencia_destino',
        'idefector',
        'idld_codigo',
        'idperiodo',
        'idtipo_agrupamiento',
        'usuario',
        'operacion',
        'foperacion'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idld_alta' => 'integer',
        'fecha_creado' => 'datetime',
        'fdesde' => 'date',
        'fhasta' => 'date',
        'fuera_termino' => 'boolean',
        'valor' => 'float',
        'info_adicional' => 'string',
        'bloqueado' => 'boolean',
        'idtipo_formulario' => 'integer',
        'idld_estado' => 'integer',
        'idld_tipo_alta' => 'integer',
        'idpuesto' => 'integer',
        'iddependencia_origen' => 'integer',
        'iddependencia_destino' => 'integer',
        'idefector' => 'integer',
        'idld_codigo' => 'integer',
        'idperiodo' => 'integer',
        'idtipo_agrupamiento' => 'integer',
        'usuario' => 'string',
        'operacion' => 'string',
        'foperacion' => 'datetime'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fecha_creado' => 'required',
        'fdesde' => 'required',
        'fuera_termino' => 'required',
        'valor' => 'required',
        'info_adicional' => 'required',
        'bloqueado' => 'required',
        'idtipo_formulario' => 'required',
        'idld_estado' => 'required',
        'idld_tipo_alta' => 'required',
        'idpuesto' => 'required',
        'iddependencia_origen' => 'required',
        'iddependencia_destino' => 'required',
        'idefector' => 'required',
        'idld_codigo' => 'required',
        'idperiodo' => 'required',
        'idtipo_agrupamiento' => 'required'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependenciaDestino()
    {
        return $this->belongsTo(\App\Models\Dependencium::class, 'iddependencia_destino');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function dependenciaOrigen()
    {
        return $this->belongsTo(\App\Models\Dependencium::class, 'iddependencia_origen');
    }

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
    public function ldCodigo()
    {
        return $this->belongsTo(\App\Models\LdCodigo::class, 'idld_codigo');
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
    public function ldTipoAlta()
    {
        return $this->belongsTo(\App\Models\LdTipoAltum::class, 'idld_tipo_alta');
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
    public function puesto()
    {
        return $this->belongsTo(\App\Models\Puesto::class, 'idpuesto');
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
    public function tipoFormulario()
    {
        return $this->belongsTo(\App\Models\TipoFormulario::class, 'idtipo_formulario');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function ldCambioEstados()
    {
        return $this->belongsToMany(\App\Models\LdCambioEstado::class, 'ld_alta_baja_relacion');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ldCambioEstado3s()
    {
        return $this->hasMany(\App\Models\LdCambioEstado::class, 'idld_alta');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ldAltaRelacions()
    {
        return $this->hasMany(\App\Models\LdAltaRelacion::class, 'idld_alta_reemplazado');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ldAltaRelacion4s()
    {
        return $this->hasMany(\App\Models\LdAltaRelacion::class, 'idld_alta_reemplazante');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ldVisadoAlta()
    {
        return $this->hasMany(\App\Models\LdVisadoAltum::class, 'idld_alta');
    }

    /**
     * @return string
     */
    public function getFechaHasta()
    {
        // Verifico que tenga una baja asociada
        $baja = LdCambioEstado::select('fhasta')
            ->where('idld_estado', 3)
            ->where('idld_alta', $this->idld_alta)
            ->whereIn('idld_tipo_cambio_estado', [2, 3, 4, 5, 6])
            ->orderByDesc('fhasta')
            ->first();

        if (isset($baja)) {
            return $baja->fhasta;
        }

        //Obtengo última continuidad que me devolverá la última fecha
        $continuidad = LdCambioEstado::select('fhasta')
            ->where('idld_estado', 3)
            ->where('idld_alta', $this->idld_alta)
            ->where('idld_tipo_cambio_estado', 1)
            ->orderByDesc('fhasta')
            ->first();

        if (isset($continuidad)) {
            return $continuidad->fhasta;
        }

        //Si no tiene ni baja ni continuidad, entonces devuelvo la fecha del vencimiento del alta
        return $this->fhasta;
    }
}
