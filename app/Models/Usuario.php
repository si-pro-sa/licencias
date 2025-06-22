<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Usuario
 * @package App\Models
 * @version February 4, 2020, 8:25 am UTC
 *
 * @property \App\Models\agente idagente
 * @property \App\Models\Perfil idperfil
 * @property \Illuminate\Database\Eloquent\Collection anioEvaluacions
 * @property \Illuminate\Database\Eloquent\Collection anioEvaluacion1s
 * @property \Illuminate\Database\Eloquent\Collection anioEvaluacion2s
 * @property \Illuminate\Database\Eloquent\Collection notificacions
 * @property \Illuminate\Database\Eloquent\Collection notificacion3s
 * @property \Illuminate\Database\Eloquent\Collection notificacion4s
 * @property \Illuminate\Database\Eloquent\Collection evaluacions
 * @property \Illuminate\Database\Eloquent\Collection evaluacion5s
 * @property \Illuminate\Database\Eloquent\Collection evaluacion6s
 * @property \Illuminate\Database\Eloquent\Collection puestoRevisions
 * @property \Illuminate\Database\Eloquent\Collection puestoRevision7s
 * @property \Illuminate\Database\Eloquent\Collection puestoRevision8s
 * @property \Illuminate\Database\Eloquent\Collection referenteDependencia
 * @property \Illuminate\Database\Eloquent\Collection referenteDependencium9s
 * @property \Illuminate\Database\Eloquent\Collection referenteDependencium10s
 * @property \Illuminate\Database\Eloquent\Collection referenteDependencium11s
 * @property \Illuminate\Database\Eloquent\Collection permissionUsers
 * @property \Illuminate\Database\Eloquent\Collection roleUsers
 * @property \Illuminate\Database\Eloquent\Collection puestoCambioEfectors
 * @property \Illuminate\Database\Eloquent\Collection puestoCambioEfector12s
 * @property \Illuminate\Database\Eloquent\Collection puestoCambioEfector13s
 * @property \Illuminate\Database\Eloquent\Collection tipoReferidos
 * @property \Illuminate\Database\Eloquent\Collection tipoReferido14s
 * @property \Illuminate\Database\Eloquent\Collection tipoReferido15s
 * @property \Illuminate\Database\Eloquent\Collection provincia
 * @property \Illuminate\Database\Eloquent\Collection provincium16s
 * @property \Illuminate\Database\Eloquent\Collection provincium17s
 * @property \Illuminate\Database\Eloquent\Collection departamentos
 * @property \Illuminate\Database\Eloquent\Collection departamento18s
 * @property \Illuminate\Database\Eloquent\Collection departamento19s
 * @property \Illuminate\Database\Eloquent\Collection localidads
 * @property \Illuminate\Database\Eloquent\Collection localidad20s
 * @property \Illuminate\Database\Eloquent\Collection localidad21s
 * @property \Illuminate\Database\Eloquent\Collection domicilios
 * @property \Illuminate\Database\Eloquent\Collection domicilio22s
 * @property \Illuminate\Database\Eloquent\Collection domicilio23s
 * @property \Illuminate\Database\Eloquent\Collection recomendacionCandidatos
 * @property \Illuminate\Database\Eloquent\Collection recomendacionCandidato24s
 * @property \Illuminate\Database\Eloquent\Collection recomendacionCandidato25s
 * @property \Illuminate\Database\Eloquent\Collection candidatos
 * @property \Illuminate\Database\Eloquent\Collection candidato26s
 * @property \Illuminate\Database\Eloquent\Collection candidato27s
 * @property \Illuminate\Database\Eloquent\Collection psicoevaluadors
 * @property \Illuminate\Database\Eloquent\Collection psicoevaluador28s
 * @property \Illuminate\Database\Eloquent\Collection psicoevaluador29s
 * @property \Illuminate\Database\Eloquent\Collection agendas
 * @property \Illuminate\Database\Eloquent\Collection agenda30s
 * @property \Illuminate\Database\Eloquent\Collection agenda31s
 * @property \Illuminate\Database\Eloquent\Collection evaluacionPsicotecnicas
 * @property \Illuminate\Database\Eloquent\Collection evaluacionPsicotecnica32s
 * @property \Illuminate\Database\Eloquent\Collection evaluacionPsicotecnica33s
 * @property \Illuminate\Database\Eloquent\Collection extendidoEvaluacionPsicotecnicas
 * @property \Illuminate\Database\Eloquent\Collection extendidoEvaluacionPsicotecnica34s
 * @property \Illuminate\Database\Eloquent\Collection extendidoEvaluacionPsicotecnica35s
 * @property \Illuminate\Database\Eloquent\Collection evaluacionTecnicas
 * @property \Illuminate\Database\Eloquent\Collection evaluacionTecnica36s
 * @property \Illuminate\Database\Eloquent\Collection evaluacionTecnica37s
 * @property \Illuminate\Database\Eloquent\Collection agente38s
 * @property \Illuminate\Database\Eloquent\Collection agente39s
 * @property \Illuminate\Database\Eloquent\Collection agente40s
 * @property \Illuminate\Database\Eloquent\Collection turnoPsicoevaluadors
 * @property \Illuminate\Database\Eloquent\Collection turnoPsicoevaluador41s
 * @property \Illuminate\Database\Eloquent\Collection turnoPsicoevaluador42s
 * @property \Illuminate\Database\Eloquent\Collection puestoAdicionals
 * @property \Illuminate\Database\Eloquent\Collection puestoAdicional43s
 * @property \Illuminate\Database\Eloquent\Collection cargos
 * @property \Illuminate\Database\Eloquent\Collection cargo44s
 * @property \Illuminate\Database\Eloquent\Collection cargo45s
 * @property \Illuminate\Database\Eloquent\Collection cargoBajaRelacions
 * @property \Illuminate\Database\Eloquent\Collection cargoBajaRelacion46s
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstados
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstado47s
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstado48s
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstadoObs
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstadoOb49s
 * @property \Illuminate\Database\Eloquent\Collection cargoCambioEstadoOb50s
 * @property \Illuminate\Database\Eloquent\Collection efectivaPrestacionCargos
 * @property \Illuminate\Database\Eloquent\Collection efectivaPrestacionCargo51s
 * @property \Illuminate\Database\Eloquent\Collection efectivaPrestacionCargo52s
 * @property \Illuminate\Database\Eloquent\Collection efectivaPrestacionObsCargos
 * @property \Illuminate\Database\Eloquent\Collection efectivaPrestacionObsCargo53s
 * @property \Illuminate\Database\Eloquent\Collection efectivaPrestacionObsCargo54s
 * @property string nombreusuario
 * @property string clave
 * @property string email
 * @property boolean activo
 * @property string usuario
 * @property string operacion
 * @property string|\Carbon\Carbon foperacion
 * @property integer idagente
 * @property integer idperfil
 * @property string password
 * @property string remember_token
 * @property string api_token
 */
class Usuario extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'idusuario';
    public $table = 'usuario';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public $connection = "pgsql_sistema";

    public $fillable = [
        'nombreusuario',
        'clave',
        'email',
        'activo',
        'usuario',
        'operacion',
        'foperacion',
        'idagente',
        'idperfil',
        'password',
        'remember_token',
        'api_token'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idusuario' => 'integer',
        'nombreusuario' => 'string',
        'clave' => 'string',
        'email' => 'string',
        'activo' => 'boolean',
        'usuario' => 'string',
        'operacion' => 'string',
        'foperacion' => 'datetime',
        'idagente' => 'integer',
        'idperfil' => 'integer',
        'password' => 'string',
        'remember_token' => 'string',
        'api_token' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [

    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idagente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function idperfil()
    {
        return $this->belongsTo(\App\Models\Perfil::class, 'idperfil');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function anioEvaluacions()
    {
        return $this->hasMany(\App\Models\anioEvaluacion::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function anioEvaluacion1s()
    {
        return $this->hasMany(\App\Models\anioEvaluacion::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function anioEvaluacion2s()
    {
        return $this->hasMany(\App\Models\anioEvaluacion::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function notificacions()
    {
        return $this->hasMany(\App\Models\notificacion::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function notificacion3s()
    {
        return $this->hasMany(\App\Models\notificacion::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function notificacion4s()
    {
        return $this->hasMany(\App\Models\notificacion::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function evaluacions()
    {
        return $this->hasMany(\App\Models\evaluacion::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function evaluacion5s()
    {
        return $this->hasMany(\App\Models\evaluacion::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function evaluacion6s()
    {
        return $this->hasMany(\App\Models\evaluacion::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestoRevisions()
    {
        return $this->hasMany(\App\Models\puestoRevision::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestoRevision7s()
    {
        return $this->hasMany(\App\Models\puestoRevision::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestoRevision8s()
    {
        return $this->hasMany(\App\Models\puestoRevision::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function referenteDependencia()
    {
        return $this->hasMany(\App\Models\referenteDependencium::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function referenteDependencium9s()
    {
        return $this->hasMany(\App\Models\referenteDependencium::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function referenteDependencium10s()
    {
        return $this->hasMany(\App\Models\referenteDependencium::class, 'idusuario');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function referenteDependencium11s()
    {
        return $this->hasMany(\App\Models\referenteDependencium::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function permissionUsers()
    {
        return $this->hasMany(\App\Models\PermissionUser::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function roleUsers()
    {
        return $this->hasMany(\App\Models\RoleUser::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestoCambioEfectors()
    {
        return $this->hasMany(\App\Models\puestoCambioEfector::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestoCambioEfector12s()
    {
        return $this->hasMany(\App\Models\puestoCambioEfector::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestoCambioEfector13s()
    {
        return $this->hasMany(\App\Models\puestoCambioEfector::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tipoReferidos()
    {
        return $this->hasMany(\App\Models\tipoReferido::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tipoReferido14s()
    {
        return $this->hasMany(\App\Models\tipoReferido::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function tipoReferido15s()
    {
        return $this->hasMany(\App\Models\tipoReferido::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function provincia()
    {
        return $this->hasMany(\App\Models\provincium::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function provincium16s()
    {
        return $this->hasMany(\App\Models\provincium::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function provincium17s()
    {
        return $this->hasMany(\App\Models\provincium::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function departamentos()
    {
        return $this->hasMany(\App\Models\departamento::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function departamento18s()
    {
        return $this->hasMany(\App\Models\departamento::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function departamento19s()
    {
        return $this->hasMany(\App\Models\departamento::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function localidads()
    {
        return $this->hasMany(\App\Models\localidad::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function localidad20s()
    {
        return $this->hasMany(\App\Models\localidad::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function localidad21s()
    {
        return $this->hasMany(\App\Models\localidad::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function domicilios()
    {
        return $this->hasMany(\App\Models\domicilio::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function domicilio22s()
    {
        return $this->hasMany(\App\Models\domicilio::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function domicilio23s()
    {
        return $this->hasMany(\App\Models\domicilio::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function recomendacionCandidatos()
    {
        return $this->hasMany(\App\Models\recomendacionCandidato::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function recomendacionCandidato24s()
    {
        return $this->hasMany(\App\Models\recomendacionCandidato::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function recomendacionCandidato25s()
    {
        return $this->hasMany(\App\Models\recomendacionCandidato::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function candidatos()
    {
        return $this->hasMany(\App\Models\candidato::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function candidato26s()
    {
        return $this->hasMany(\App\Models\candidato::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function candidato27s()
    {
        return $this->hasMany(\App\Models\candidato::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function psicoevaluadors()
    {
        return $this->hasMany(\App\Models\psicoevaluador::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function psicoevaluador28s()
    {
        return $this->hasMany(\App\Models\psicoevaluador::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function psicoevaluador29s()
    {
        return $this->hasMany(\App\Models\psicoevaluador::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function agendas()
    {
        return $this->hasMany(\App\Models\agenda::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function agenda30s()
    {
        return $this->hasMany(\App\Models\agenda::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function agenda31s()
    {
        return $this->hasMany(\App\Models\agenda::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function evaluacionPsicotecnicas()
    {
        return $this->hasMany(\App\Models\evaluacionPsicotecnica::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function evaluacionPsicotecnica32s()
    {
        return $this->hasMany(\App\Models\evaluacionPsicotecnica::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function evaluacionPsicotecnica33s()
    {
        return $this->hasMany(\App\Models\evaluacionPsicotecnica::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function extendidoEvaluacionPsicotecnicas()
    {
        return $this->hasMany(\App\Models\extendidoEvaluacionPsicotecnica::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function extendidoEvaluacionPsicotecnica34s()
    {
        return $this->hasMany(\App\Models\extendidoEvaluacionPsicotecnica::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function extendidoEvaluacionPsicotecnica35s()
    {
        return $this->hasMany(\App\Models\extendidoEvaluacionPsicotecnica::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function evaluacionTecnicas()
    {
        return $this->hasMany(\App\Models\evaluacionTecnica::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function evaluacionTecnica36s()
    {
        return $this->hasMany(\App\Models\evaluacionTecnica::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function evaluacionTecnica37s()
    {
        return $this->hasMany(\App\Models\evaluacionTecnica::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function agente38s()
    {
        return $this->hasMany(\App\Models\agente::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function agente39s()
    {
        return $this->hasMany(\App\Models\agente::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function agente40s()
    {
        return $this->hasMany(\App\Models\agente::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function turnoPsicoevaluadors()
    {
        return $this->hasMany(\App\Models\turnoPsicoevaluador::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function turnoPsicoevaluador41s()
    {
        return $this->hasMany(\App\Models\turnoPsicoevaluador::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function turnoPsicoevaluador42s()
    {
        return $this->hasMany(\App\Models\turnoPsicoevaluador::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestoAdicionals()
    {
        return $this->hasMany(\App\Models\puestoAdicional::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestoAdicional43s()
    {
        return $this->hasMany(\App\Models\puestoAdicional::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargos()
    {
        return $this->hasMany(\App\Models\cargo::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargo44s()
    {
        return $this->hasMany(\App\Models\cargo::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargo45s()
    {
        return $this->hasMany(\App\Models\cargo::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoBajaRelacions()
    {
        return $this->hasMany(\App\Models\cargoBajaRelacion::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoBajaRelacion46s()
    {
        return $this->hasMany(\App\Models\cargoBajaRelacion::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstados()
    {
        return $this->hasMany(\App\Models\cargoCambioEstado::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstado47s()
    {
        return $this->hasMany(\App\Models\cargoCambioEstado::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstado48s()
    {
        return $this->hasMany(\App\Models\cargoCambioEstado::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstadoObs()
    {
        return $this->hasMany(\App\Models\cargoCambioEstadoOb::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstadoOb49s()
    {
        return $this->hasMany(\App\Models\cargoCambioEstadoOb::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function cargoCambioEstadoOb50s()
    {
        return $this->hasMany(\App\Models\cargoCambioEstadoOb::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function efectivaPrestacionCargos()
    {
        return $this->hasMany(\App\Models\efectivaPrestacionCargo::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function efectivaPrestacionCargo51s()
    {
        return $this->hasMany(\App\Models\efectivaPrestacionCargo::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function efectivaPrestacionCargo52s()
    {
        return $this->hasMany(\App\Models\efectivaPrestacionCargo::class, 'deleted_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function efectivaPrestacionObsCargos()
    {
        return $this->hasMany(\App\Models\efectivaPrestacionObsCargo::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function efectivaPrestacionObsCargo53s()
    {
        return $this->hasMany(\App\Models\efectivaPrestacionObsCargo::class, 'updated_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function efectivaPrestacionObsCargo54s()
    {
        return $this->hasMany(\App\Models\efectivaPrestacionObsCargo::class, 'deleted_by');
    }
}
