<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\CargoTipoCese;
use Illuminate\Support\Facades\DB;
use App\Models\ContadorHorasPuesto;
use App\Models\ContadorHorasGuardia;
use Illuminate\Support\Facades\Auth;
use App\Models\ContadorHorasReemplazo;
use App\Models\ContadorHorasGuardiaEspecial;
use Illuminate\Database\Eloquent\Collection;
use App\Models\ContadorHorasLibreDisponibilidad;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Puesto
 * @package App\Models
 * @version January 2, 2019, 7:24 pm UTC
 *
 * @property integer idpuesto
 * @property integer iddependencia
 * @property integer idtipo_funcion
 * @property integer idtipo_nivel
 * @property integer idagente
 * @property string usuario
 * @property string operacion
 * @property string|Carbon foperacion
 * @property integer idtipo_agrupamiento
 * @property integer idtipo_planta
 * @property integer idtipo_especialidad
 * @property boolean visto
 * @property date fdesde
 * @property date fhasta
 * @property boolean requiere_revision
 * @property integer iddependencia_servicio
 * @property BelongsTo|Dependencia dependencia
 * @property BelongsTo|TipoPlanta tipoPlanta
 * @property HasMany puestosAdicionales
 * @property BelongsTo|TipoNivel tipoNivel
 */
class Puesto extends Model
{
    public $table = 'puesto';
    protected $primaryKey = 'idpuesto';
    protected $connection = 'pgsql_public';
    public $timestamps = false;
    protected $appends = ['efector', 'hora_desde_formateada', 'hora_hasta_formateada', 'tipo_dia_formateado', 'cantidad_horas_formateado', 'horario_trabajo_formateado'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'iddependencia',
        'idtipo_funcion',
        'idtipo_nivel',
        'idagente',
        'usuario',
        'operacion',
        'foperacion',
        'idtipo_agrupamiento',
        'idtipo_planta',
        'idtipo_especialidad',
        'visto',
        'fdesde',
        'fhasta',
        'requiere_revision',
        'iddependencia_servicio'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'idpuesto' => 'integer',
        'iddependencia' => 'integer',
        'idtipo_funcion' => 'integer',
        'idtipo_nivel' => 'integer',
        'idagente' => 'integer',
        'usuario' => 'string',
        'operacion' => 'string',
        'idtipo_agrupamiento' => 'integer',
        'idtipo_planta' => 'integer',
        'idtipo_especialidad' => 'integer',
        'visto' => 'boolean',
        'fdesde' => 'datetime:d/m/Y',
        'fhasta' => 'datetime:d/m/Y',
        'requiere_revision' => 'boolean',
        'iddependencia_servicio' => 'integer'
    ];

    protected $dates = [
        'fdesde',
        'fhasta',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'fdesde' => 'required',
        'iddependencia' => 'required',
    ];


    /**
     * @return BelongsTo
     **/
    public function agente()
    {
        return $this->belongsTo(\App\Models\Agente::class, 'idagente');
    }

    /**
     * @return BelongsTo|Dependencia
     **/
    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'iddependencia', 'iddependencia');
    }

    /**
     * @return Dependencia|BelongsTo
     */
    public function dependencia_servicio()
    {
        return $this->belongsTo(Dependencia::class, 'iddependencia_servicio', 'iddependencia');
    }

    /**
     * @return BelongsTo
     **/
    public function tipoAgrupamiento()
    {
        return $this->belongsTo(TipoAgrupamiento::class, 'idtipo_agrupamiento', 'idtipo_agrupamiento');
    }

    /**
     * @return BelongsTo
     **/
    public function tipoEspecialidad()
    {
        return $this->belongsTo(\App\Models\TipoEspecialidad::class, 'idtipo_especialidad', 'idtipo_especialidad');
    }

    /**
     * @return BelongsTo
     **/
    public function tipoFuncion()
    {
        return $this->belongsTo(\App\Models\TipoFuncion::class, 'idtipo_funcion', 'idtipo_funcion');
    }

    /**
     * @return BelongsTo
     **/
    public function tipoNivel()
    {
        return $this->belongsTo(\App\Models\TipoNivel::class, 'idtipo_nivel', 'idtipo_nivel');
    }

    /**
     * @return BelongsTo|TipoPlanta
     **/
    public function tipoPlanta()
    {
        return $this->belongsTo(\App\Models\TipoPlanta::class, 'idtipo_planta', 'idtipo_planta');
    }

    /**
     * @return BelongsTo
     **/
    public function cese()
    {
        return $this->hasOne(\App\Models\CesePuesto::class, 'idpuesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     **/
    public function tipoFuncionJerarquicas()
    {
        return $this->belongsToMany(\App\Models\TipoFuncionJerarquica::class, 'funcion_jerarquica');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     **/
    public function horario_historico()
    {
        return $this->hasOne(\App\Models\HorarioPuestoHistorico::class, 'idpuesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\morphMany
     **/
    public function horarios()
    {
        return $this->morphMany(\App\Models\HorarioPuesto::class, 'puesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ldAlta()
    {
        return $this->hasMany(\App\Models\LdAlta::class, 'idpuesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function guardiaLineas()
    {
        return $this->hasMany(\App\Models\GuardiaLinea::class, 'idpuesto');
    }

    /**
     * @return HasMany
     **/
    public function puestosAdicionales()
    {
        return $this->hasMany(\App\Models\PuestoAdicional::class, 'idpuesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function razonCese()
    {
        return in_array($this->cese->idtipo_cese, $this->getTiposCeseVisibles()) && isset($this->cese->tipoCese->tipocese) ? $this->cese->tipoCese->tipocese : '';
    }

    /**
     * @param $query
     * @param $dni string
     */
    public function scopeDocumento($query, string $documento)
    {
        if (trim($documento) != '') {
            $query->whereHas('agente', function ($q) use ($documento) {
                $q->where('documento', $documento);
            })
                ->whereNull('fhasta');
        }
    }

    public function scopeActivo($query)
    {
        return $query->where('fhasta', null)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function getTiposCeseVisibles(): array
    {
        return (array) CargoTipoCese::pluck('idtipo_cese')->all();
    }

    /**
     * Cuento horas de un agente
     * @param string $fecha
     * @return array
     * @throws Exception
     */
    public function getCantidadHorasEnPeriodo(?string $fecha): array
    {
        $periodo = Periodo::getPeriodoConFecha($fecha);

        $agente = $this->agente;
        $contadorHorasFormulario = new ContadorHorasFormulario($agente, $periodo);

        $contadorHorasPuesto = new ContadorHorasPuesto($contadorHorasFormulario);
        //($idagente, $fechaDesdeNueva, $fechaHastaNueva, string $tipoFormulario, $periodoSolo = true, $periodo = null, $horasNuevas = 0, $idtipo_campania = 0, $idtipo_guardia = 0)
        $contadorHorasGuardia = new ContadorHorasGuardia($contadorHorasFormulario, $contadorHorasPuesto);
        $contadorHorasGuardiaEspecial = new ContadorHorasGuardiaEspecial($contadorHorasFormulario, $contadorHorasPuesto);
        $contadorHorasLibreDisponibilidad = new ContadorHorasLibreDisponibilidad($contadorHorasFormulario, $contadorHorasPuesto);
        $contadorHorasReemplazo = new ContadorHorasReemplazo($contadorHorasFormulario, $contadorHorasPuesto);

        $horas = [
            'puesto' => $contadorHorasPuesto->getTotalHorasPeriodo(),
            'libre_disponibilidad' => $contadorHorasLibreDisponibilidad->getTotalHorasPeriodo(),
            'guardia' => $contadorHorasGuardia->getTotalHorasPeriodo(),
            'guardia_especial' => $contadorHorasGuardiaEspecial->getTotalHorasPeriodo(),
            'reemplazo' => $contadorHorasReemplazo->getTotalHorasPeriodo(),
            'guardia_real' => $contadorHorasGuardia->getTotalRealHorasPeriodo(),
            'guardia_especial_real' => $contadorHorasGuardiaEspecial->getTotalRealHorasPeriodo(),

            'horas_maximas_puesto' => $contadorHorasPuesto->getHorasMaximas(),
            'horas_maximas_reemplazos' => $contadorHorasReemplazo->getHorasMaximas(),
            'horas_maximas_libres' => $contadorHorasLibreDisponibilidad->getHorasMaximas(),
            'horas_maximas_guardias' => $contadorHorasGuardia->getHorasMaximas()
        ];

        $horas['total'] = ($horas['puesto'] + $horas['libre_disponibilidad'] + $horas['guardia'] + $horas['reemplazo']);
        $horas['total_real'] = ($horas['puesto'] + $horas['libre_disponibilidad'] + $horas['guardia_real'] + $horas['guardia_especial_real'] + $horas['reemplazo']);

        return $horas;
    }

    public function cerrarPuesto($fecha)
    {
        $fecha = !isset($fecha) ? date('Y-m-d', strtotime('-1 day')) : $fecha;
        return $this->update(['fhasta' => $fecha, 'usuario' => Auth::user()->nombreusuario]);
    }

    public function getDependencias(): array
    {
        $dependencias[] = HorarioDependencia::getHorarioDependencia($this);
        foreach ($this->puestosAdicionales as $pa) {
            $dependencias[] = HorarioDependencia::getHorarioDependencia($pa);
        }

        return $dependencias;
    }

    /**
     * @return array
     */
    public function getDependenciasReemplazos(): array
    {
        $periodo = Periodo::getPeriodoConFecha(null);
        $dependencias = Reemplazo::where('idpuesto_reemplazante', $this->idpuesto)
            ->where('aprobado', true)
            ->where('idperiodo', $periodo->idperiodo)
            ->leftJoin('dependencia', function ($join) {
                $join->on('dependencia.iddependencia', '=', 'reemplazo.iddependencia');
            })
            ->groupBy('dependencia.iddependencia')
            ->select(DB::raw("dependencia.iddependencia,CONCAT(dependencia.codigorrhh,'-',dependencia.dependencia) as dependencia"))
            ->get();

        $tmpDependencias = '';
        foreach ($dependencias as $dependencia) {
            try {
                $tmpDependencias .= $dependencia->dependencia . ', ';
            } catch (\Exception $exception) {
                $tmpDependencias .= '';
            }
        }
        return ($tmpDependencias == '') ? ['dependencias' => 'NO', 'count' => 'NO'] : ['dependencias' => $tmpDependencias, 'count' => 'SI'];
    }

    /**
     * @return Collection
     */
    public function getReemplazos(): Collection
    {
        $reemplazos = Reemplazo::where('idpuesto_reemplazante', $this->idpuesto)
            ->where('aprobado', true)
            ->get('idpuesto_reemplazante');

        return $reemplazos;
    }

    /**
     * @return array
     */
    public function getDependenciasLibres(): array
    {
        $periodo = Periodo::getPeriodoConFecha(null);
        $dependencias = LdAlta::where('idpuesto', $this->idpuesto)
            ->where('idld_estado', 3)
            ->where('idperiodo', $periodo->idperiodo)
            ->leftJoin('dependencia', function ($join) {
                $join->on('dependencia.iddependencia', '=', 'ld_alta.iddependencia_destino');
            })
            ->groupBy('dependencia.iddependencia')
            ->select(DB::raw("dependencia.iddependencia,CONCAT(dependencia.codigorrhh,'-',dependencia.dependencia) as dependencia"))
            ->get();

        $tmpDependencias = '';
        foreach ($dependencias as $dependencia) {
            try {
                $tmpDependencias .= $dependencia->dependencia . ', ';
            } catch (\Exception $exception) {
                $tmpDependencias .= '';
            }
        }
        return ($tmpDependencias == '') ? ['dependencias' => 'NO', 'count' => 'NO'] : ['dependencias' => $tmpDependencias, 'count' => 'SI'];
    }

    /**
     * @return Collection
     */
    public function getLibres(): Collection
    {
        $altas = LdAlta::with([
            'dependenciaDestino',
        ])
            ->where('idpuesto', $this->idpuesto)
            ->where('idld_estado', 3)
            ->get('idpuesto');
        if (isset($altas)) {
            return $altas;
        } else {
            return new Collection();
        }
    }

    /**
     * @return array
     */
    public function getDependenciasGuardiasAgente(): array
    {
        $periodo = Periodo::getPeriodoConFecha(null);
        $dependencias = GuardiaLinea::where('idpuesto', $this->idpuesto)
            ->where('guardia.idperiodo', $periodo->idperiodo)
            ->whereIn('idguardia_tipo_estado_linea', [1, 3, 4])
            ->leftJoin('guardia', function ($join) {
                $join->on('guardia.idguardia', '=', 'guardia_linea.idguardia');
            })
            ->leftJoin('dependencia', function ($join) {
                $join->on('guardia.idservicio', '=', 'dependencia.iddependencia');
            })
            ->groupBy('dependencia.iddependencia')
            ->select(DB::raw("dependencia.iddependencia,CONCAT(dependencia.codigorrhh,'-',dependencia.dependencia) as dependencia"))
            ->get();

        $tmpDependencias = '';
        foreach ($dependencias as $dependencia) {
            /** @var GuardiaLinea $dependencia */
            try {
                $tmpDependencias .= $dependencia->dependencia . ', ';
            } catch (\Exception $exception) {
                $tmpDependencias .= '';
            }
        }

        return ($tmpDependencias == '') ? ['dependencias' => 'NO', 'count' => 'NO'] : ['dependencias' => $tmpDependencias, 'count' => 'SI'];
    }

    /**
     * @return Collection
     */
    public function getGuardias(): Collection
    {
        $guardias = GuardiaLinea::with('guardia')
            ->with('guardia.efector')
            ->with('guardia.servicio')
            ->where('idpuesto', $this->idpuesto)
            ->whereIn('idguardia_tipo_estado_linea', [1, 3, 4])
            ->get();

        return $guardias;
    }

    /**
     * @return array
     */
    public function getDependenciasCoberturasAgente(): array
    {
        $horas = $this->getCantidadHorasEnPeriodo(null);
        if ($horas['puesto'] == 0) {
            $resultado = ['dependencias' => 'NO', 'count' => 'NO'];
        } else {
            if ($this->iddependencia != null) {
                $resultado = ['dependencias' => $this->dependencia->codigorrhh . ' ' . $this->dependencia->dependencia, 'count' => 'SI'];
            } else {
                $resultado = ['dependencias' => 'Sin Datos', 'count' => 'SI'];
            };
        };
        return $resultado;
    }

    public function existeInterposicionHoraria(string $fecha, string $horaDesde, string $horaHasta): bool
    {
        if (!Feriado::isFeriado($fecha)) {
            if ($this->existeInterposicionHorariaPuesto($fecha, $horaDesde, $horaHasta)) {
                return true;
            }

            foreach ($this->puestosAdicionales as $puestoAdicional) {
                if ($this->existeInterposicionHorariaPuesto($fecha, $horaDesde, $horaHasta, $puestoAdicional)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function existeInterposicionHorariaPuesto(string $fecha, string $horaDesde, string $horaHasta, $puesto = null): bool
    {
        if (!isset($puesto)) {
            $puesto = $this;
        }

        $numeroDia = (int) date('N', strtotime($fecha));
        $diaSiguienteFeriado = Feriado::isFeriado(date('Y-m-d', strtotime($fecha . ' +1 days')));

        //Chequeo interposición de horarios del nuevo sistema
        //Lunes a Viernes
        $horarioR = $puesto->horarios()->rotativo()->first();
        if (isset($horarioR)) {
            return false;
        }

        //Chequeo interposición de horarios del nuevo sistema
        //Lunes a Viernes
        $horario = $puesto->horarios()->lunesViernes()->first();
        if (isset($horario)) {
            if (($numeroDia > 0 && $numeroDia < 6 && $diaSiguienteFeriado) || $numeroDia === 5) {
                $rango1 = new RangoTiempo($horaDesde, $horaHasta, true);
                $rango2 = new RangoTiempo($horario->hora_desde, $horario->hora_hasta, true);
                if ($rango1->tieneInterposicionConRango($rango2)) {
                    return true;
                }
            } elseif ($numeroDia > 0 && $numeroDia < 5) {
                $rango1 = new RangoTiempo($horaDesde, $horaHasta);
                $rango2 = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);
                if ($rango1->tieneInterposicionConRango($rango2)) {
                    return true;
                }
            }
        } else {
            //Personalizado
            $horariosP = $puesto->horarios()->personalizado()->get();
            foreach ($horariosP as $horario) {
                if (
                    $horario->idtipo_dia < 8 && $horario->idtipo_dia === $numeroDia
                    && ($diaSiguienteFeriado || $horariosP->where('idtipo_dia', $horario->idtipo_dia + 1)->count() === 0)
                ) {
                    $rango1 = new RangoTiempo($horaDesde, $horaHasta, true);
                    $rango2 = new RangoTiempo($horario->hora_desde, $horario->hora_hasta, true);
                    if ($rango1->tieneInterposicionConRango($rango2)) {
                        return true;
                    }
                } elseif ($horario->idtipo_dia < 8 && $horario->idtipo_dia === $numeroDia) {
                    $rango1 = new RangoTiempo($horaDesde, $horaHasta);
                    $rango2 = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);
                    if ($rango1->tieneInterposicionConRango($rango2)) {
                        return true;
                    }
                }
            }

            //Personalizado Guardia
            $horariosPG = $puesto->horarios()->personalizadoGuardia()->get();
            foreach ($horariosPG as $horario) {
                if ($horario->idtipo_dia > 10 && $horario->idtipo_dia === $numeroDia + 10 && ($diaSiguienteFeriado || $horariosPG->where('idtipo_dia', $horario->idtipo_dia + 1)->count() === 0)) {
                    $rango1 = new RangoTiempo($horaDesde, $horaHasta, true);
                    $rango2 = new RangoTiempo($horario->hora_desde, $horario->hora_hasta, true);
                    if ($rango1->tieneInterposicionConRango($rango2)) {
                        return true;
                    }
                } elseif ($horario->idtipo_dia > 10 && $horario->idtipo_dia === $numeroDia + 10) {
                    $rango1 = new RangoTiempo($horaDesde, $horaHasta);
                    $rango2 = new RangoTiempo($horario->hora_desde, $horario->hora_hasta);
                    if ($rango1->tieneInterposicionConRango($rango2)) {
                        return true;
                    }
                }
            }

            //Si no posee horarios del nuevo sistema, chequeo interposición de horarios con el HISTÓRICO
            if (get_class($puesto) === 'App\Models\Puesto' && count($horariosP) === 0 && count($horariosPG) === 0 && isset($puesto->horario_historico)) {
                $horarioHistorico = $puesto->horario_historico;
                if (isset($horarioHistorico)) {
                    $diaTrabajo = $horarioHistorico->numero_dia_trabajo;
                    if (
                        isset($diaTrabajo) && is_int($diaTrabajo) &&
                        (($numeroDia > 0 && $numeroDia < 6 && $diaSiguienteFeriado
                            && (($diaTrabajo === 8 && $numeroDia === 5)
                                || ($diaTrabajo === $numeroDia && $diaSiguienteFeriado)))
                            || ($diaTrabajo === 8 && $numeroDia === 5)
                            || ($diaTrabajo === $numeroDia && $diaSiguienteFeriado))
                    ) {
                        $rango1 = new RangoTiempo($horaDesde, $horaHasta, true);
                        $rango2 = new RangoTiempo($horarioHistorico->hora_desde, $horarioHistorico->hora_hasta, true);
                        if ($rango1->tieneInterposicionConRango($rango2)) {
                            return true;
                        }
                    } elseif (isset($diaTrabajo) && is_int($diaTrabajo) && (($diaTrabajo === $numeroDia)
                        || ($diaTrabajo === 8 && $numeroDia > 0 && $numeroDia < 5))) {
                        $rango1 = new RangoTiempo($horaDesde, $horaHasta);
                        $rango2 = new RangoTiempo($horarioHistorico->hora_desde, $horarioHistorico->hora_hasta);
                        if ($rango1->tieneInterposicionConRango($rango2)) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public function getEfectorAttribute(): string
    {
        $efectorCompuesto = $this->puestosAdicionales()->count() > 0 ? '(C)' : '';
        if (isset($this->dependencia)) {
            $nombrePadre = $this->dependencia->getPadre();
            return "{$nombrePadre} {$efectorCompuesto}";
        }
        return '';
    }

    public function getHoraDesdeFormateadaAttribute(): string
    {
        //Obtengo horario sistema nuevo solo de lunes a viernes, lunes a domingo o rotativos
        $horario = $this->horarios()
            ->lunesViernesOrRotativo()
            ->puestoPrincipal($this->idpuesto)
            ->first();

        if (isset($horario)) {
            return $horario->hora_desde;
        }

        $horariosPuestoPrincipal = $this->horarios()
            ->personalizado()
            ->puestoPrincipal($this->idpuesto)
            ->count();

        if ($horariosPuestoPrincipal > 0) {
            return 'P';
        }

        $horariosPuestoPrincipal = $this->horarios()
            ->personalizadoGuardia()
            ->puestoPrincipal($this->idpuesto)
            ->count();

        if ($horariosPuestoPrincipal > 0) {
            return 'PG';
        }

        //Obtengo horarios de sistema histórico si no tiene horarios cargados
        $horarioHistorico = $this->horario_historico;

        if (isset($horarioHistorico)) {
            return isset($horarioHistorico->hora_desde) ? date('H:i', strtotime($horarioHistorico->hora_desde)) : '';
        }
        return '';
    }

    public function getHoraHastaFormateadaAttribute(): string
    {
        //Obtengo horario sistema nuevo solo de lunes a viernes, lunes a domingo o rotativos
        $horario = $this->horarios()
            ->lunesViernesOrRotativo()
            ->puestoPrincipal($this->idpuesto)
            ->first();

        if (isset($horario)) {
            return $horario->hora_hasta;
        }

        $horariosPuestoPrincipal = $this->horarios()
            ->personalizado()
            ->puestoPrincipal($this->idpuesto)
            ->count();

        if ($horariosPuestoPrincipal > 0) {
            return 'P';
        }

        $horariosPuestoPrincipal = $this->horarios()
            ->personalizadoGuardia()
            ->puestoPrincipal($this->idpuesto)
            ->count();

        if ($horariosPuestoPrincipal > 0) {
            return 'PG';
        }

        //Obtengo horarios de sistema histórico si no tiene horarios cargados
        $horarioHistorico = $this->horario_historico;

        if (isset($horarioHistorico)) {
            return isset($horarioHistorico->hora_hasta) ? date('H:i', strtotime($horarioHistorico->hora_hasta)) : '';
        }
        return '';
    }

    public function getTipoDiaFormateadoAttribute(): string
    {
        //Obtengo horario sistema nuevo solo de lunes a viernes, lunes a domingo o rotativos
        $horario = $this->horarios()
            ->lunesViernesOrRotativo()
            ->puestoPrincipal($this->idpuesto)
            ->first();

        if (isset($horario)) {
            return $horario->tipoDia->tipodia;
        }

        $horariosPuestoPrincipal = $this->horarios()
            ->personalizado()
            ->puestoPrincipal($this->idpuesto)
            ->count();

        if ($horariosPuestoPrincipal > 0) {
            return 'Personalizado';
        }

        $horariosPuestoPrincipal = $this->horarios()
            ->personalizadoGuardia()
            ->puestoPrincipal($this->idpuesto)
            ->count();

        if ($horariosPuestoPrincipal > 0) {
            return 'Personalizado Guardia';
        }

        //Obtengo horarios de sistema histórico si no tiene horarios cargados
        $horarioHistorico = $this->horario_historico;

        if (isset($horarioHistorico)) {
            return $horarioHistorico->tipoHorario->tipohorario ?? '';
        }
        return '';
    }

    public function getCantidadHorasFormateadoAttribute(): string
    {
        //Obtengo horario sistema nuevo solo de lunes a viernes, lunes a domingo o rotativos
        $horario = $this->horarios()
            ->lunesViernesOrRotativo()
            ->puestoPrincipal($this->idpuesto)
            ->first();

        if (isset($horario)) {
            return $horario->cantidad_horas;
        }

        if (isset($horario)) {
            return $horario->cantidad_horas;
        }

        $horariosPuestoPrincipal = $this->horarios()
            ->personalizado()
            ->puestoPrincipal($this->idpuesto)
            ->get()
            ->pluck('cantidad_horas')
            ->median();

        if ($horariosPuestoPrincipal > 0) {
            return $horariosPuestoPrincipal;
        }

        $horariosPuestoPrincipal = $this->horarios()
            ->personalizadoGuardia()
            ->puestoPrincipal($this->idpuesto)
            ->get()
            ->pluck('cantidad_horas')
            ->median();

        if ($horariosPuestoPrincipal > 0) {
            return $horariosPuestoPrincipal;
        }
        return '0';
    }

    public function getHorarioTrabajoFormateadoAttribute(): string
    {
        $horariosAdicionales = $this->puestosAdicionales()->count() > 0 ? '(C)' : '';
        //Obtengo horario sistema nuevo solo de lunes a viernes, lunes a domingo o rotativos
        $horario = $this->horarios()
            ->lunesViernesOrRotativo()
            ->puestoPrincipal($this->idpuesto)
            ->first();

        if (isset($horario)) {
            return "{$horario->hora_desde} a {$horario->hora_hasta} <br>({$horario->tipoDia->tipodia_corto}) {$horariosAdicionales}";
        }

        $horariosPuestoPrincipal = $this->horarios()
            ->personalizado()
            ->puestoPrincipal($this->idpuesto)
            ->count();

        if ($horariosPuestoPrincipal > 0) {
            return "Personalizado {$horariosAdicionales}";
        }

        $horariosPuestoPrincipal = $this->horarios()
            ->personalizadoGuardia()
            ->puestoPrincipal($this->idpuesto)
            ->count();

        if ($horariosPuestoPrincipal > 0) {
            return "Personalizado Guardia {$horariosAdicionales}";
        }

        //Obtengo horarios de sistema histórico si no tiene horarios cargados
        $horarioHistorico = $this->horario_historico;

        if (isset($horarioHistorico)) {
            $horario['hora_desde'] = isset($horarioHistorico->hora_desde) ? date('H:i', strtotime($horarioHistorico->hora_desde)) : null;
            $horario['hora_hasta'] = isset($horarioHistorico->hora_hasta) ? date('H:i', strtotime($horarioHistorico->hora_hasta)) : null;
            $horario['numero_dia'] = $horarioHistorico->numero_dia_trabajo;

            $horario['dias'] = $horario['numero_dia'] === 8 ? 'LaV' : ($horario['numero_dia'] === 8 ? 'ROT' : ($horarioHistorico->dias_guardia ?? ''));
            if (isset($horario['dias'], $horario['hora_desde'], $horario['hora_hasta'])) {
                return "{$horario['hora_desde']} a {$horario['hora_hasta']} <br>({$horario['dias']}) {$horariosAdicionales}";
            }
        }
        return '';
    }

    /*
    *   Plantas Visibles en Agentes de Planta
    */
    public static function getPlantasAgentesdePlanta(): array
    {
        return [1, 2, 6, 7, 11, 12];
    }

    public function format()
    {
        $pAdicionales = [];
        foreach ($this->puestosAdicionales as $pa) {
            $pAdicionales[] = [
                'dependencia' => ($pa->dependencia->codigo_nombre ?? ''),
                'dependencia_padre' => (isset($pa->dependencia) ? $pa->dependencia->getPadre() : ''),
            ];
        }
        return [
            'idpuesto' => $this->idpuesto,
            'fdesde' => (isset($this->fdesde) ? $this->fdesde->format('d/m/Y') : ''),
            'fhasta' => (isset($this->fhasta) ? $this->fhasta->format('d/m/Y') : ''),
            'dependencia' => ($this->dependencia->codigo_nombre) ?? '',
            'dependencia_padre' => (isset($this->dependencia) ? $this->dependencia->getPadre() : ''),
            'tipo_nivel' => ($this->tipoNivel->tiponivel ?? ''),
            'tipo_planta' => ($this->tipoPlanta->tipoplanta ?? ''),
            'tipo_funcion' => ($this->tipoFuncion->tipofuncion ?? ''),
            'tipo_agrupamiento' => ($this->tipoAgrupamiento->tipoagrupamiento ?? ''),
            'tipo_cese' => ($this->cese->tipoCese->tipocese ?? ''),
            'puestosAdicionales' => $pAdicionales
        ];
    }

    public function formatTotalPuestos()
    {
        return [
            'idpuesto' => $this->idpuesto,
            'idagente' => $this->idagente,
            'documento' => $this->agente->documento,
            'apellido' => $this->agente->apellido,
            'nombre' => $this->agente->nombre,
            'sexo' => ($this->agente->tipoSexo->tiposexo ?? ''),
            'fecha_nacimiento' => (isset($this->fnacimiento) ? $this->fnacimiento->format('d/m/Y') : ''),
            'cuil' => $this->agente->cuil,
            'fdesde' => (isset($this->fdesde) ? $this->fdesde->format('d/m/Y') : ''),
            'fhasta' => (isset($this->fhasta) ? $this->fhasta->format('d/m/Y') : ''),
            'tipo_planta' => ($this->tipoPlanta->tipoplanta ?? ''),
            'tipo_agrupamiento' => ($this->tipoAgrupamiento->tipoagrupamiento ?? ''),
            'tipo_funcion' => ($this->tipoFuncion->tipofuncion ?? ''),
            'tipo_nivel' => ($this->tipoNivel->tiponivel ?? ''),
            'dependencia_padre1' => (isset($this->dependencia) ? $this->dependencia->getPadre() : ''),
            'servicio1' => ($this->dependencia->codigo_nombre) ?? '',
            'dependencia_padre2' => (isset($this->puestosAdicionales[0]->dependencia) ? $this->puestosAdicionales[0]->dependencia->getPadre() : ''),
            'servicio2' => ($this->puestosAdicionales[0]->dependencia->codigo_nombre) ?? '',
            'dependencia_padre3' => (isset($this->puestosAdicionales[1]->dependencia) ? $this->puestosAdicionales[1]->dependencia->getPadre() : ''),
            'servicio3' => ($this->puestosAdicionales[1]->dependencia->codigo_nombre) ?? '',
            'dependencia_padre4' => (isset($this->puestosAdicionales[2]->dependencia) ? $this->puestosAdicionales[2]->dependencia->getPadre() : ''),
            'servicio4' => ($this->puestosAdicionales[2]->dependencia->codigo_nombre) ?? '',
            'dependencia_padre5' => (isset($this->puestosAdicionales[3]->dependencia) ? $this->puestosAdicionales[3]->dependencia->getPadre() : ''),
            'servicio5' => ($this->puestosAdicionales[3]->dependencia->codigo_nombre) ?? '',
        ];
    }

    public function formatTotalHorasFormularios($fecha): array
    {
        $horas = $this->getCantidadHorasEnPeriodo($fecha);
        return [
            'idpuesto' => $this->idpuesto,
            'idagente' => $this->idagente,
            'documento' => $this->agente->documento,
            'apellido' => $this->agente->apellido,
            'nombre' => $this->agente->nombre,
            'puesto' => $horas['puesto'],
            'libre_disponibilidad' => $horas['libre_disponibilidad'],
            'guardia' => $horas['guardia'],
            'guardia_especial' => $horas['guardia_especial'],
            'reemplazo' => $horas['reemplazo'],
            'guardia_real' => $horas['guardia_real'],
            'total' => $horas['total'],
            'total_real' => $horas['total_real'],
            '-------------------',
            'horas_maximas_puesto' => $horas['horas_maximas_puesto'],
            'horas_maximas_reemplazos' => $horas['horas_maximas_reemplazos'],
            'horas_maximas_libres' => $horas['horas_maximas_libres'],
            'horas_maximas_guardias' => $horas['horas_maximas_guardias'],
        ];
    }

    public function getDiagramasPorPerdiodo($fdesde, $fhasta)
    {

        $diagramasDelPeriodo = [];

        $diagramas = ScoreDiagrama::where('idpuesto', $this->idpuesto)->orderBy('fecha')->get();

        $fechadesde = strtotime($fdesde);
        $fechahasta = strtotime($fhasta);

        foreach ($diagramas as $diagrama) {
            $fecha = strtotime(date($diagrama->fecha));

            if ($fechadesde <= $fecha && $fecha <= $fechahasta) {
                $diagramasDelPeriodo[] = $diagrama;
            }
        }
        return $diagramasDelPeriodo;
    }

    public function getDiagramasPorIDPerdiodo($idperiodo)
    {
        $periodo = Periodo::select('fdesde', 'fhasta')->where('idperiodo', $idperiodo)->first();

        return $this->getDiagramasPorPerdiodo($periodo->fdesde, $periodo->fhasta);
    }
}
