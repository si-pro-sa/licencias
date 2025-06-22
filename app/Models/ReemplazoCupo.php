<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

/**
 * Class Reemplazo_Cupo
 * @package App\Models
 * @version Febrary 19, 2020, 12:43 am
 *
 * @property integer idreemplazo_cupo
 * @property integer idefector
 * @property integer idperiodo
 * @property integer old_idreemplazo_cupo
 * @property integer cupo_max
 * @property integer cupo_totales
 * @property integer cupo_restantes
 * @property string usuario
 * @property string observaciones
 * @property bool estado
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 * @property integer created_by
 * @property integer updated_by
 */
class ReemplazoCupo extends Model
{
    public $table = 'reemplazo_cupo';
    protected $primaryKey = 'idreemplazo_cupo';
    public $timestamps = false;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public $fillable = [
        'idefector',
        'idperiodo',
        'old_idreemplazo_cupo',
        'isareaoperativa',
        'isareaprogramatica',
        'isredservicio',
        'cupo_max',
        'cupo_totales',
        'cupo_restantes',
        'observaciones',
    ];

    /**
     * The attributes that should be casted to native types.
     * @var array
     */
    protected $casts = [
        'idreemplazo_cupo' => 'integer',
        'idefector' => 'integer',
        'idperiodo' => 'integer',
        'old_idreemplazo_cupo' => 'integer',
        'cupo_max' => 'integer',
        'cupo_totales' => 'integer',
        'cupo_restantes' => 'integer',
        'isareaoperativa' => 'boolean',
        'isareaprogramatica' => 'boolean',
        'isredservicio' => 'boolean',
        'usuario' => 'string',
        'observaciones' => 'string',
    ];

    /**
     * Validation rules
     * @var array
     */
    public static $rules = [
        'idefector' => 'required',
        'idperiodo' => 'required',
        'cupo_max' => 'required',
        'cupo_totales' => 'required',
        'cupo_restantes' => 'required',
    ];

    /**
     * @return BelongsTo
     **/
    public function dependencia()
    {
        return $this->belongsTo(Dependencia::class, 'idefector');
    }

    /**
     * @return BelongsTo
     **/
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'idperiodo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestosReemplazoCupo()
    {
        return $this->hasMany(ReemplazoCupoPuesto::class, 'idreemplazo_cupo_puesto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'created_by');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function updatedBy()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'updated_by');
    }

    public function createReemplazoCupo(ReemplazoCupo $reemplazoCupo, $data)
    {
        $reemplazoCupo->idefector = $data->idefector;
        $reemplazoCupo->idperiodo = $data->idperiodo;
        $reemplazoCupo->cupo_max = $data->cupoEfector;
        $reemplazoCupo->cupo_totales = $data->cuposTotales;
        $reemplazoCupo->cupo_restantes = $data->cuposRestantes;
        $reemplazoCupo->isredservicio = $data->isRedServicio;
        $reemplazoCupo->isareaoperativa = $data->isAreaOperativa;
        $reemplazoCupo->isareaprogramatica = $data->isAreaProgramatica;
        $reemplazoCupo->observaciones = $data->observaciones;
        $reemplazoCupo->usuario = Auth()->user()->nombreusuario;
        $reemplazoCupo->created_by = Auth()->user()->idusuario;
        $reemplazoCupo->updated_by = Auth()->user()->idusuario;
        $reemplazoCupo->saveOrFail();
    }

    /**
     * Obtiene puestos por servicio con sus cupos ya cargados
     * (Formateados)
     * @param int $idservicio
     * @return Collection
     */
    public static function obtenerPuestosPorFunciones(int $idservicio): Collection
    {
        $puestos = ReemplazoCupo::getCuposPorPuestoByServicio($idservicio);
        $funciones = [];

        if ($puestos->count() > 0) {
            return $puestos;
        } else {

            $funciones = DB::table('puesto as p')
                ->join("dependencia as d", "p.iddependencia", '=', "d.iddependencia")
                ->join("tipo_puesto as tp", "p.idtipo_puesto", '=', "tp.idtipo_puesto")
                ->leftJoin("reemplazo_cupo_puesto as rcp", "tp.idtipo_puesto", "=", "rcp.idtipo_puesto")
                ->select(
                    "p.iddependencia",
                    "d.dependencia",
                    "tp.tipo_puesto",
                    "tp.idtipo_puesto",
                    // "tgf.idtipo_grupo_funcion",
                    "rcp.cupo",
                    "rcp.eventual",
                    "rcp.observaciones"
                )
                ->where("p.iddependencia", $idservicio)
                ->distinct()
                ->get();

            if ($funciones) {
                foreach ($funciones as $p) {
                    $p->cupo = null;
                    $p->eventual = false;
                    $p->observaciones = '';
                }
            }
            return $funciones;
        }
    }

    /**
     * Carga los cupos de los servicios que ya cuentan con cupos
     * y devulve tantos los que tienen cupos como los que no
     * @param int $idservicio
     * @return Collection
     */
    public static function getCuposPorPuestoByServicio(int $idservicio): Collection
    {
        $bandera = 0;

        ## puesto por servicio
        $puestos = ReemplazoCupo::getPuestosPorServicio($idservicio);
        ## puestos por servicio con sus cupos
        $cupos = ReemplazoCupo::getPuestosPorServicioConSusCupos($idservicio);

        foreach ($puestos as $puesto) {
            // bandera para evitar duplicados
            if ($bandera != $puesto->idtipo_puesto) {
                foreach ($cupos as $cupo) {
                    $bandera = $puesto->idtipo_puesto;
                    if ($puesto->idtipo_puesto == $cupo->idtipo_puesto) {
                        // seteo el cupo si es que ya tienen datos cargados
                        $puesto->cupo = $cupo->cupo;
                        $puesto->eventual = $cupo->eventual;
                        $puesto->observaciones = $cupo->observaciones;
                    }
                }
            } else {
                continue;
            }
        }
        // dd($puestos, $cupos, $data);
        return $puestos;
    }

    /**
     * Obtiene los puesto que se encuentran en ese servicio
     * @param int $idservicio
     * @return Collection
     */
    public static function getPuestosPorServicio(int $idservicio): Collection
    {
        $puestos = DB::table('puesto as p')
            ->join("dependencia as d", "p.iddependencia", '=', "d.iddependencia")
            ->join("tipo_puesto as tp", "p.idtipo_puesto", '=', "tp.idtipo_puesto")
            ->select(
                "p.iddependencia",
                "d.dependencia",
                "tp.tipo_puesto",
                "tp.idtipo_puesto",
                // "tgf.idtipo_grupo_funcion"
            )
            ->where("p.iddependencia", $idservicio)
            ->distinct()
            ->get();

        // agrego las props cupo y eventual en null
        foreach ($puestos as $puesto) {
            $puesto->cupo = null;
            $puesto->eventual = false;
            $puesto->observaciones = '';
        }
        return $puestos;
    }

    /**
     * Obtiene los puestos por servicios con sus cupos ya cargados
     * @param int $idservicio
     * @return Collection
     */
    public static function getPuestosPorServicioConSusCupos(int $idservicio): Collection
    {
        $cupos = DB::table('reemplazo_cupo_puesto as rcp')
            ->join("tipo_puesto as tp", "rcp.idtipo_puesto", '=', "tp.idtipo_puesto")
            ->join("reemplazo_cupo as rc", "rcp.idreemplazo_cupo", '=', "rc.idreemplazo_cupo")
            ->select(
                "tp.idtipo_puesto",
                "tp.tipo_puesto",
                "rcp.cupo",
                "rcp.eventual",
                "rcp.observaciones"
            )
            ->where("rcp.idservicio", $idservicio)
            ->distinct()
            ->get();

        return $cupos;
    }

    public static function obtenerTotalAgentesEfector(int $idefector, int $idGrupofuncion): int
    {
        $cantidadAgentesEfector = 0;
        $cantidadAgentesEfector = Dependencia::getCantidadAgentesTipoPuesto($idefector, $idGrupofuncion);
        return $cantidadAgentesEfector["efector"];
    }

    public static function obtenerTotalAgentesServicio(int $idservicio, int $idtipo_puesto): int
    {
        $cantidadAgentesServicio = 0;
        $cantidadAgentesServicio = Dependencia::getCantidadAgentesTipoPuesto($idservicio, $idtipo_puesto);
        return $cantidadAgentesServicio["servicio"];
    }

    public static function getInfoCupoDependencia(int $idefector)
    {
        $infoCupoDependencia = DB::table('reemplazo_cupo as rc')
            ->join('periodo as p', 'rc.idperiodo', 'p.idperiodo')
            ->join('dependencia as e', 'rc.idefector', 'e.iddependencia')
            ->select(
                "rc.cupo_max as cupoEfector",
                "rc.cupo_totales",
                "rc.cupo_restantes",
                "e.dependencia as efector",
                "e.iddependencia as idefector",
                "p.periodo",
                "p.idperiodo",
                "rc.observaciones"
            )
            ->where('e.iddependencia', $idefector)
            // ->distinct("rcp.idreemplazo_cupo_puesto")
            // ->groupBy("rcp.idreemplazo_cupo")
            ->orderBy("rc.idreemplazo_cupo", "desc")
            ->first();

        return $infoCupoDependencia;
    }

    /**
     * Actualizar el cupo del efector y restar los cupos ya asigandos
     * @property int $cupo
     * @property int $id
     * @return array cupoActualizado
     */
    public static function actualizarCupoEfector($cupo, $id): array
    {
        $reemplazoCupo = ReemplazoCupo::find($id);
        $cupoRestantes = intval($cupo) - (intval($reemplazoCupo->cupo_max) - intval($reemplazoCupo->cupo_restantes));
        if ($cupoRestantes > 0) {
            $newReemplazoCupo = ReemplazoCupo::newReemplazoCupoVigente($reemplazoCupo, $cupo, $cupoRestantes);
            $reemplazoCupo->estado = false;
            $reemplazoCupo->updateOrFail();
            return ["cupo_max" => $newReemplazoCupo->cupo_max, "cupo_restantes" => $newReemplazoCupo->cupo_restantes];
        }

        // return ["cupo_max" => $reemplazoCupo->cupo_max, "cupo_restantes" => $reemplazoCupo->cupo_restantes];
    }

    public static function newReemplazoCupoVigente(ReemplazoCupo $reemplazo, int $cupo, int $cupoRestantes) {
        $newReemplazoCupo = new ReemplazoCupo();
        $newReemplazoCupo->idefector = $reemplazo->idefector;
        $newReemplazoCupo->idperiodo = $reemplazo->idperiodo;
        $newReemplazoCupo->old_idreemplazo_cupo = $reemplazo->idreemplazo_cupo;
        $newReemplazoCupo->cupo_max = $cupo;
        $newReemplazoCupo->cupo_totales = $cupo;
        $newReemplazoCupo->cupo_restantes = $cupoRestantes;
        $newReemplazoCupo->isredservicio = $reemplazo->isRedServicio;
        $newReemplazoCupo->isareaoperativa = $reemplazo->isAreaOperativa;
        $newReemplazoCupo->isareaprogramatica = $reemplazo->isAreaProgramatica;
        $newReemplazoCupo->observaciones = $reemplazo->observaciones;
        $newReemplazoCupo->usuario = Auth()->user()->nombreusuario;
        $newReemplazoCupo->created_by = $reemplazo->created_by;
        $newReemplazoCupo->updated_by = Auth()->user()->idusuario;
        $newReemplazoCupo->created_at = $reemplazo->created_at;
        $newReemplazoCupo->updated_at = date('Y-m-d H:i:s');
        $newReemplazoCupo->saveOrFail();

        if ($newReemplazoCupo) {
            $cupoPuesto = ReemplazoCupoPuesto::where('idreemplazo_cupo', $reemplazo->idreemplazo_cupo)->get();
            foreach ($cupoPuesto as $cupo) {
                $cupo->old_idreemplazo_cupo = $cupo->idreemplazo_cupo;
                $cupo->idreemplazo_cupo = $newReemplazoCupo->idreemplazo_cupo;
                $cupo->update();
            }
        }

        return $newReemplazoCupo;
    }

    /**
     * Actualizar el cupo restantes
     * @property int $cupo
     * @property int $id
     * @return array cupoActualizado
     */
    public static function actualizarCupoRestantes($cupo, $id): array
    {
        $acualizarCupoRestantes = ReemplazoCupo::where('idreemplazo_cupo', $id)->first();
        $cupoPuesto = ReemplazoCupoPuesto::where('idreemplazo_cupo', $id)->first();
        $cupoRestantes = (intval($acualizarCupoRestantes->cupo_restantes) + intval($cupoPuesto->cupo)) - intval($cupo);

        if ($cupoRestantes > 0 && $acualizarCupoRestantes->cupo_totales >= $cupoRestantes) {
            $acualizarCupoRestantes->cupo_restantes = $cupoRestantes;
            $acualizarCupoRestantes->usuario = Auth()->user()->nombreusuario;
            $acualizarCupoRestantes->created_by = Auth()->user()->idusuario;
            $acualizarCupoRestantes->updated_by = Auth()->user()->idusuario;
            $acualizarCupoRestantes->updateOrFail();
            return ["cupo_restantes" => $cupoRestantes, "status" => true];
        } else {
            return ["error" => "No se puede asignar más cupos que los cupos totales.", "status" => false];
        }
    }

    public static function actualizarCuposRestantesMismoEfector($idefector, $cupos_restantes)
    {
        $actualizarCuposRestantesMismoEfector = ReemplazoCupo::where('idefector', $idefector)->get();

        // actualizo los cupos restantes del mismo efector
        if ($actualizarCuposRestantesMismoEfector) {
            foreach ($actualizarCuposRestantesMismoEfector as $actualizarReemplazoCupoRestante) {
                // $actualizarReemplazoCupoRestante = ReemplazoCupo::where($actualizarReemplazoCupoRestante->idefector, $idefector)->first();
                $actualizarReemplazoCupoRestante->cupo_restantes = $cupos_restantes;
                $actualizarReemplazoCupoRestante->updateOrFail();
            }
        }
    }

    /**
     * Modificar observacion
     * @param int $idreemplazo_cupo
     * @return bool
     */
    public static function modificarObservacion(string $observacion_interna, int $idreemplazo_cupo): bool
    {
        $reeCupoPuesto = ReemplazoCupoPuesto::find($idreemplazo_cupo);

        $obs = ReemplazoCupo::find($reeCupoPuesto->idreemplazo_cupo);
        $obs->observaciones = $observacion_interna;
        $obs->update();

        $observacion = ReemplazoCupoPuesto::where('idreemplazo_cupo_puesto', $idreemplazo_cupo)->first();
        $observacion->observaciones = $observacion_interna;
        $observacion->update();

        return $obs && $observacion ? true : false;
    }

    /**
     * Obtener efector formateado
     * @param int idefector
     */
    public function getEfectorFormateado(int $idefector): string
    {
        $efector = Dependencia::select('dependencia')->where('idefector', $idefector)->first();
        return $efector;
    }

    /**
     * Obtener cupos que no son eventuales
     */
    public static function getReemplazosCupoSinEventual() {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $periodoReeplica = null;
        $perActual = $meses[date('n') - 1] . '/' . date('Y');
        $perAnterior = $meses[date('n') - 2] . '/' . date('Y');
        $periodo = Periodo::orderByDesc('idperiodo')
                            ->select('idperiodo')
                            ->where('periodo', $perActual)->orWhere('periodo', $perAnterior)->first();

        $ree = ReemplazoCupo::with(['periodo'])
                                ->whereHas('periodo', function ($que) use ($perActual, $perAnterior) {
                                    $que->where('periodo', $perActual)
                                        ->orWhere('periodo', $perAnterior)
                                        ->select('idperiodo');
                                })
                                ->where('estado', true)
                                ->get();
        // dd($perActual, $perAnterior, $periodo, $ree);
        // return ReemplazoCupo::where('estado', true)->where('idperiodo', $periodo->idperiodo)->get(); 
        return $ree;
    }

    /**
     * create reemplazo cupo replica
     */
    public static function createReemplazoCupoReplica($dataRee, $idperiodo)
    {
        foreach ($dataRee as $data) {
            $newReemplazoCupoReplica = new ReemplazoCupo;
            $newReemplazoCupoReplica->idefector = $data->idefector;
            $newReemplazoCupoReplica->idperiodo = $idperiodo;
            $newReemplazoCupoReplica->old_idreemplazo_cupo = $data->idreemplazo_cupo;
            $newReemplazoCupoReplica->cupo_max = $data->cupo_max;
            $newReemplazoCupoReplica->cupo_totales = $data->cupo_totales;
            $newReemplazoCupoReplica->cupo_restantes = $data->cupo_restantes;
            $newReemplazoCupoReplica->isredservicio = $data->isredservicio;
            $newReemplazoCupoReplica->isareaoperativa = $data->isareaoperativa;
            $newReemplazoCupoReplica->isareaprogramatica = $data->isareaprogramatica;
            $newReemplazoCupoReplica->observaciones = $data->observaciones;
            $newReemplazoCupoReplica->usuario = Auth()->user()->nombreusuario;
            $newReemplazoCupoReplica->created_by = Auth()->user()->idusuario;
            $newReemplazoCupoReplica->updated_by = Auth()->user()->idusuario;
            $newReemplazoCupoReplica->saveOrFail();
        }
    }

    /**
   * Obtener reemplazo cupos
   */
  public static function getReemplazoCupo() {
    $result = ReemplazoCupo::all();
    if (count($result) > 0) {
        return $result;
    }
    return [];
  }
}
