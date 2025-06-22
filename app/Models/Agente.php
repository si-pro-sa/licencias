<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\belongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;
use DateTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\RecomendacionCandidato;

/**
 * Class Agente
 * @package App\Models
 * @version November 27, 2018, 7:18 am UTC
 *
 * @property integer idagente
 * @property string apellido
 * @property string nombre
 * @property integer documento
 * @property bigInteger cuil
 * @property integer idtipo_sexo
 * @property string|Carbon fnacimiento
 * @property string|Carbon falta
 * @property string|Carbon fingreso_admpub
 * @property string usuario
 * @property string operacion
 * @property string|Carbon foperacion
 * @property integer idlocalidad
 * @property string codigopostal
 * @property string localid
 * @property string email
 * @property string telefono
 * @property string celular
 *
 * @property HasMany puestos
 * @property HasMany puestosAdicionales
 * @property MorphMany|EvaluacionTecnica[] psicotecnicos
 * @property MorphMany|EvaluacionTecnica[] evalTecnicas
 * @property BelongsTo|Domicilio domicilio
 * @property HasOne|PsicoEvaluador psicoevaluador
 * @property MorphMany|RecomendacionCandidato[] recomendacion
 * @property MorphMany ultimarecomendacion
 * @property MorphMany cargos
 */
class Agente extends Model
{
    public $table = 'agente';
    protected $primaryKey = 'idagente';
    protected $connection = 'pgsql_public';

    protected $appends = ['antiguedad_formateada', 'edad', 'sexo_formateado', 'apellido_nombre'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by'];

    protected $dates = ['fnacimiento', 'falta'];

    public $fillable = [
        'apellido',
        'nombre',
        'documento',
        'cuil',
        'idtipo_sexo',
        'fnacimiento',
        'falta',
        'fingreso_admpub',
        'usuario',
        'operacion',
        'foperacion',
        'domicilio',
        'iddomicilio',
        'idlocalidad',
        'codigopostal',
        'localid',
        'email',
        'telefono',
        'celular',
        'antiguedad'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idagente' => 'integer',
        'apellido' => 'string',
        'nombre' => 'string',
        'documento' => 'integer',
        'idtipo_sexo' => 'integer',
        'fnacimiento' => 'date',
        'falta' => 'date',
        'fingreso_admpub' => 'date',
        'usuario' => 'string',
        'operacion' => 'string',
        'domicilio' => 'string',
        'idlocalidad' => 'integer',
        'codigopostal' => 'string',
        'localid' => 'string',
        'email' => 'string',
        'antiguedad' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rulesContact = [
        'celular' => 'required|string',
        'telefono' => 'nullable',
        'email' => 'email|nullable',
    ];


    /**
     * @param $query
     * @param $dni string
     */
    public function scopeDnis($query, string $dni)
    {
        if (trim($dni) != '') {
            $query->where('documento', 'like', '%' . $dni . '%');
        }
    }
    /**
     * @param $query
     * @param $nombre string
     */
    public function scopeNombres($query, string $nombre)
    {
        if (trim($nombre) != '') {
            $raw = DB::raw('LOWER(CONCAT(apellido,nombre))');
            $query->where($raw, 'like', '%' . strtolower($nombre) . '%');
        }
    }
    /**
     * @param $query
     * @param Domicilio $domicilio
     */
    public function scopeAgenteFromDomicilio($query, $domicilio)
    {
        $domicilios = Domicilio::departamento($domicilio->iddepartamento)->localidadscope($domicilio->idlocalidad)->get()->toArray();
        $iddomicilios = array_column($domicilios, 'iddomicilio');
        if (is_array($iddomicilios)) {
            $query->whereIn('iddomicilio', $iddomicilios);
        }
    }
    /**
     * @param $query
     * @param array $recomendacion
     */
    public function scopeRecomendacions($query, $recomendacion)
    {
        if (is_array($recomendacion)) {
            $query->whereIn('idagente', $recomendacion);
        }
    }
    /**
     * @param $query
     * @param string $dni
     */
    public function scopeDni($query, $dni)
    {
        if (trim($dni) != '') {
            $query->where('documento', 'like', '%' . $dni . '%');
        }
    }
    /**
     * @param $query
     * @param string $dni
     */
    public function scopeAgente($query, $agente)
    {
        if (trim($agente) != '') {
            $raw = DB::raw('LOWER(CONCAT(documento,nombre,apellido))');
            $query->where($raw, 'like', '%' . $agente . '%');
        }
    }

    /**
     * @return BelongsTo
     **/
    public function localidad()
    {
        return $this->belongsTo(\App\Models\Localidad::class);
    }

    /**
     * @return HasMany
     */
    public function licencias()
    {
        return $this->hasMany(\App\Models\Licencia::class, 'idlicencia');
    }

    /**
     * @return BelongsTo
     **/
    public function tipoSexo()
    {
        return $this->belongsTo(\App\Models\TipoSexo::class, 'idtipo_sexo');
    }

    /**
     * @return belongsToMany|AgenteTitulo[]
     **/
    public function titulos()
    {
        return $this->belongsToMany(Agente::class, 'agentetitulo', 'idagente', 'idtitulo');
    }

    /**
     * @return BelongsToMany
     **/
    public function agenteTitulos()
    {
        return $this->hasMany(\App\Models\AgenteTitulo::class, 'idagente', 'idagente');
    }

    /**
     * @return int
     **/
    public function getPrimerTitulo()
    {
        $titulo = $this->agenteTitulos()->first();
        if (isset($titulo)) {
            return $titulo->idtitulo;
        }
    }

    /**
     * @return int
     **/
    public function getPrimerTituloNombre()
    {
        $agentesTitulo = $this->agenteTitulos()->first();
        if (isset($agentesTitulo)) {
            return $agentesTitulo->titulo->titulo;
        }
        return '';
    }

    public function isEvaluador()
    {
        $psicoevaluador = $this->psicoevaluador;
        if (!empty($psicoevaluador)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return HasMany
     **/
    public function puestos()
    {
        return $this->hasMany(\App\Models\Puesto::class, 'idagente');
    }

    /**
     * @return MorphMany
     **/
    public function psicotecnicos()
    {
        return $this->morphMany('App\Models\EvaluacionPsicotecnica', 'evaluacion_psicotecnica');
    }
    /**
     * @return MorphMany
     **/
    public function evalTecnicas()
    {
        return $this->morphMany('App\Models\EvaluacionTecnica', 'candidato');
    }

    /**
     * @return MorphMany
     **/
    public function turnosPsicotecnicos()
    {
        return $this->morphMany('App\Models\TurnoPsicoEvaluador', 'candidato');
    }

    /**
     * @return MorphMany
     **/
    public function turnosPsicotecnicosEnCurso()
    {
        return $this->morphMany('App\Models\TurnoPsicoEvaluador', 'candidato')->orderBy('created_at');
    }

    /**
     * @return BelongsTo
     **/
    public function domicilio()
    {
        return $this->belongsTo('App\Models\Domicilio', 'iddomicilio');
    }

    /**
     * @return BelongsTo
     **/
    public function domicilioWithDefault()
    {
        return $this->belongsTo('App\Models\Domicilio', 'iddomicilio')->withDefault(Domicilio::$default);
    }

    /**
     * @return HasOne
     **/
    public function psicoevaluador()
    {
        return $this->hasOne(PsicoEvaluador::class, 'idagente');
    }

    /**
     * @return MorphMany|RecomendacionCandidato[]
     **/
    public function recomendacion()
    {
        return $this->morphMany(RecomendacionCandidato::class, 'candidato');
    }

    /**
     * @return mixed|RecomendacionCandidato
     */
    public function ultimaRecomendacion()
    {
        return $this->morphMany(RecomendacionCandidato::class, 'candidato')->orderBy('created_at')->first();
    }

    public function primerPuesto()
    {
        return $this->puestos->sortBy('fdesde')->first();
    }

    /**
     * @return Puesto
     */
    public function ultimoPuesto()
    {
        return $this->puestos->sortByDesc('fdesde')->first();
    }

    /**
     * @return MorphMany
     **/
    public function cargos()
    {
        return $this->morphMany(\App\Models\Cargo::class, 'agente_propuesto');
    }

    //@todo Habilitar condición para que muestre solo altas visadas
    public function ultimoCargo()
    {
        return $this->cargos()
            ->with([
                'cargoReemplazado',
                'efector',
                'servicio',
                'tipoFuncion',
                'tipoNivel',
                'tipoAgrupamiento',
                'titulo',
                'tipoCampania',
                'tipoEspecialidad',
                'tipoCese',
                'cargoCambioEstados'
            ])
            ->whereHas('cargoCambioEstados', function (Builder $query) {
                $query->where('idcargo_tipo_formulario', '<', '3')
                    ->where('idcargo_tipo_visado', '=', '8')
                    ->orderByDesc('fecha_desde');
            })
            ->first();
    }

    public function tieneCargoActivo(): bool
    {
        $bajasVigentes = $this->cargos()->bajasVigentes()->count();
        if ($bajasVigentes > 0) {
            return true;
        }

        $altasOrContinuidadesVigentes = $this->cargos()->altasOrContinuidadesVigentes()->count();
        if ($altasOrContinuidadesVigentes > 0) {
            return true;
        }

        return false;
    }
    public function tieneCargoVisadoTitular(): bool
    {
        $cantidadCargos = Cargo::whereHas('cargoReemplazado', function (Builder $query) {
            $query->whereHas('puesto', function ($que) {
                $que->whereHas('agente', function ($q) {
                    $q->where('idagente', $this->idagente);
                });
            });
        })
            ->whereHas('cargoCambioEstados', function ($q) {
                $q->where('idcargo_tipo_formulario', '<', '3')
                    ->where('idcargo_tipo_visado', '=', '8')
                    ->orderByDesc('fecha_desde');
            })
            ->count();

        if ($cantidadCargos > 0) {
            return true;
        }

        return false;
    }
    public function puestoActivo()
    {
        return $this->puestos->count() > 0 ? $this->puestos->sortByDesc('fdesde')->where('fhasta', null)->first() : null;
    }

    public function psicotecnicoAprobado()
    {
        $ultimoPsicotecnico = $this->psicotecnicos->sortByDesc('fecha_evaluacion')->first();

        if (isset($ultimoPsicotecnico) && ($ultimoPsicotecnico->idtipo_recomendacion === 1 || $ultimoPsicotecnico->idtipo_recomendacion === 2)) {
            return true;
        }
        return false;
    }

    public function ingreso()
    {
        $puesto = $this->primerPuesto();
        if (isset($puesto)) {
            $fdesde = ' desde ' . date('d/m/Y', strtotime($puesto->fdesde));
            $fhasta = $puesto->fhasta ? (' hasta ' . date('d/m/Y', strtotime($puesto->fhasta))) : ' SIN TÉRMINO';
            $data = $puesto->tipoPlanta->tipoplanta . $fdesde . $fhasta;
            return $data;
        }
        return '-';
    }

    public function ultimaPostulacion()
    {
        $recomendacion = $this->recomendacion->sortByDesc('created_at')->first();
        return (self::exists($recomendacion)) ? $recomendacion : null;
    }

    public function lugarDeTrabajo()
    {
        $puesto = $this->ultimoPuesto();
        if (isset($puesto)) {
            return $puesto->dependencia->dependencia;
        }
    }

    public function scopeDocumento($query, int $documento)
    {
        return $query->where('documento', $documento);
    }

    public static function buscarUltimoPuestoCerrado(int $documento)
    {
        return Puesto::with('agente')
            ->whereNotNull('fhasta')
            ->orderByDesc('fhasta')
            ->whereHas('agente', function ($query) use ($documento) {
                $query->where('documento', $documento);
            })->first();
    }

    public function puestosAdicionales()
    {
        $puesto = $this->puestoActivo();
        return isset($puesto) ? $puesto->puestosAdicionales() : null;
    }

    public function edad()
    {
        $fecha_actual = new DateTime();
        $edad = $fecha_actual->diff($this->fnacimiento)->y;
        return $edad;
    }

    public function getApellidoNombreAttribute()
    {
        return $this->apellido . ' ' . $this->nombre;
    }

    public function getFechaNacimientoAttribute()
    {
        return $this->fnacimiento->format('d/m/Y');
    }

    public function getEdadAttribute()
    {
        return Carbon::parse($this->fnacimiento)->age;
    }

    public function getSexoFormateadoAttribute()
    {
        return $this->tipoSexo->abreviatura ?? '';
    }

    public function getAntiguedadFormateadaAttribute(): string
    {
        $years = ((int)$this->antiguedad) / 12;
        $anios = (int)$years;
        $meses = round(($years - (int)$years) * 12);

        //Formateo como mostrar años y meses en plural o singular
        if ($anios == 1 && $meses == 1) {
            return "${anios} año, ${meses} mes";
        } elseif ($anios == 1 && $meses > 1) {
            return "${anios} año, ${meses} meses";
        } elseif ($anios > 1 && $meses == 1) {
            return "${anios} años, ${meses} mes";
        } elseif ($anios < 1 && $meses == 1) {
            return "${meses} mes";
        } elseif ($anios < 1 && $meses > 1) {
            return "${meses} meses";
        } elseif ($anios == 1 && $meses < 1) {
            return "${anios} año";
        } elseif ($anios > 1 && $meses < 1) {
            return "${anios} años";
        }

        return "${anios} años, ${meses} meses";
    }

    public function format()
    {
        $puesto = $this->puestos->get(0);
        $depPadre = isset($puesto->dependencia) ? $puesto->dependencia->getPadre() : '';
        return [
            'APELLIDO' => $this->apellido,
            'NOMBRE' => $this->nombre,
            'DOCUMENTO' => $this->documento,
            'FUNCION' => $puesto->tipoFuncion->tipofuncion ?? '',
            'PLANTA' => $puesto->tipoPlanta->tipoplanta ?? '',
            'NIVEL' => $puesto->tipoNivel->tiponivel ?? '',
            'AGRUPAMIENTO' => $puesto->tipoAgrupamiento->tipoagrupamiento ?? '',
            'FECHA_DESDE' => $puesto->fdesde,
            'FECHA_HASTA' => $puesto->fhasta,
            'CODIGORRHH' => $puesto->dependencia->codigorrhh ?? '',
            'EFECTOR' => $depPadre ?? '',
            'DEPENDENCIA' => $puesto->dependencia->dependencia ?? '',
            'HORARIO' => $puesto->horario_old->tipoHorario->tipohorario ?? '',
            'HORA_DESDE' => $puesto->horario_old->hora_desde ?? '',
            'HORA_HASTA' => $puesto->horario_old->hora_hasta ?? '',
            'TITULO' => $this->getPrimerTituloNombre(),
            'ESPECIALIDAD' => $puesto->tipoEspecialidad->tipoespecialidad ?? '',
        ];
    }

    /**
     * Get the capacitaciones.
     */
    public function capacitacionesAgente()
    {
        return $this->hasMany(App\Models\CapacitacionAgente::class);
    }

    /**
     * Get the department ID and child dependencies based on the agent's current position.
     *
     * @return array
     */
    public function getDepartmentDetails()
    {
        $idDependencia = $this->puestos()
            ->where('fhasta', '=', null)
            ->pluck('iddependencia')
            ->first();

        if ($this->display_name === 'Dpto./Oficina Personal Dir. Gral. Red De Servicios') {
            $idDependencia = Dependencia::where('dependencia', '=', 'DIRECCION GENERAL DE RED DE SERVICIOS')
                ->pluck('iddependencia')
                ->first();
        }

        $dependenciaPadre = Dependencia::find($idDependencia);
        $dependenciaPadre = $dependenciaPadre ? $dependenciaPadre->getPadre() : null;

        $idsDependenciasHijas = [];
        if ($dependenciaPadre) {
            $dependencia = Dependencia::where('dependencia', '=', $dependenciaPadre)->first();
            if ($dependencia) {
                $idsDependenciasHijas = $dependencia->getIdsDescendencia();
            }
        }

        return [
            'IdDependencia' => $idDependencia,
            'DependenciaPadre' => $dependenciaPadre,
            'IdsDependenciasHijas' => $idsDependenciasHijas
        ];
    }
}
