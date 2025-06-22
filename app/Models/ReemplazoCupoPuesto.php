<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class ReemplazoCupoPuesto
 * @package App\Models
 * @version Febrary 19, 2023, 14:43 pm
 *
 * @property integer idreemplazo_cupo_puesto
 * @property integer idservicio
 * @property integer idtipo_puesto
 * @property integer idreemplazo_cupo
 * @property integer idperiodo
 * @property integer cupo
 * @property boolean eventual
 * @property integer totalAgentesEfector
 * @property integer totalAgentesServicio
 * @property string puesto
 * @property string observaciones
 * @property bool estado
 * @property integer old_idreemplazo_cupo
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 * @property integer created_by
 * @property integer updated_by
 */
class ReemplazoCupoPuesto extends Model
{
    public $table = 'reemplazo_cupo_puesto';
    protected $primaryKey = 'idreemplazo_cupo_puesto';
    public $timestamps = false;
    public $idusuario;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const DELETED_AT = 'deleted_at';

    public $fillable = [
        'idservicio',
        'idtipo_puesto',
        'idperiodo',
        'idreemplazo_cupo',
        'cupo',
        'eventual',
        'totalAgentesEfector',
        'totalAgentesServicio',
        'puesto',
        'observaciones',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    /**
     * The attributes that should be casted to native types.
     * @var array
     */
    protected $casts = [
        'idreemplazo_cupo_puesto' => 'integer',
        'idservicio' => 'integer',
        'idtipo_puesto' => 'integer',
        'idperiodo' => 'integer',
        'idreemplazo_cupo' => 'integer',
        'cupo' => 'integer',
        'eventual' => 'boolean',
        'totalAgentesEfector' => 'integer',
        'totalAgentesServicio' => 'integer',
        'puesto' => 'string',
    ];

    /**
     * Validation rules
     * @var array
     */
    public static $rules = [
        'idservicio' => 'required',
        'idtipo_puesto' => 'required',
        'idreemplazo_cupo' => 'required',
        'cupo' => 'required',
        'totalAgentesEfector' => 'required',
        'totalAgentesServicio' => 'required',
    ];

    // protected $appends = ['eventual'];

    // public function getEventualAttribute(){
    //     return $this->eventual ? "EVENTUAL":"";
    // }

    /**
     * @return BelongsTo
     **/
    public function servicio()
    {
        return $this->belongsTo(Dependencia::class, 'idservicio');
    }

    /**
     * @return BelongsTo
     **/
    public function reemplazocupo()
    {
        return $this->belongsTo(ReemplazoCupo::class, 'idreemplazo_cupo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function puestos()
    {
        return $this->hasMany(TipoPuesto::class, 'idtipo_puesto');
    }

    /**
     * @return BelongsTo
     **/
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'idperiodo');
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

    // public function createCupoPuesto($cupos_servicio, int $idreemplazo_cupo, $observaciones)
    public function createCupoPuesto($cupos_servicio, $reemplazo_cupo, $observaciones)
    {
        foreach ($cupos_servicio as $data) {
            $idusuario = Auth()->user()->idusuario;
            $cupoPuesto = new ReemplazoCupoPuesto();
            $cupoPuesto->idservicio = $data["idservicio"];
            $cupoPuesto->idtipo_puesto = $data["idtipo_puesto"];
            $cupoPuesto->idreemplazo_cupo = $reemplazo_cupo->idreemplazo_cupo;
            $cupoPuesto->idperiodo = $reemplazo_cupo->idperiodo;
            $cupoPuesto->cupo = $data["cupo"];
            $cupoPuesto->eventual = $data["eventual"];
            $cupoPuesto->totalagentesefector = $data["totalAgentesEfector"];
            $cupoPuesto->totalagentesservicio = $data["totalAgentesServicio"];
            $cupoPuesto->puesto = $data["puesto"];
            $cupoPuesto->observaciones = $observaciones;
            $cupoPuesto->created_by = $idusuario ?? Auth()->user()->idusuario;
            $cupoPuesto->updated_by = $idusuario ?? Auth()->user()->idusuario;
            $cupoPuesto->saveOrFail();
        }
    }
    
    public function addCupoEfectorExistente($cuposPuestos, int $idreemplazo_cupo)
    {
        foreach ($cuposPuestos as $data) {
            $cupoPuesto = new ReemplazoCupoPuesto();
            $cupoPuesto->idservicio = $data["idservicio"];
            $cupoPuesto->idtipo_puesto = $data["idtipo_puesto"];
            $cupoPuesto->idreemplazo_cupo = $idreemplazo_cupo;
            $cupoPuesto->cupo = $data["cupo"];
            $cupoPuesto->eventual = $data["eventual"];
            $cupoPuesto->totalagentesefector = $data["totalAgentesEfector"];
            $cupoPuesto->totalagentesservicio = $data["totalAgentesServicio"];
            $cupoPuesto->puesto = $data["puesto"];
            $cupoPuesto->saveOrFail();
        }
    }

    public function updateCupoPuesto($cupos_servicio, int $idreemplazo_cupo)
    {
        foreach ($cupos_servicio as $data) {
            $idservicio = $data["idservicio"];
            $idtipo_puesto = $data["idtipo_puesto"];
            $cupoPuesto = ReemplazoCupoPuesto::where("idservicio", $idservicio)->where("idtipo_puesto", $idtipo_puesto)->first();
            if ($cupoPuesto && $cupoPuesto->idservicio == $idservicio && $cupoPuesto->idtipo_puesto == $idtipo_puesto) {
                $cupoPuesto->idservicio = $data["idservicio"];
                $cupoPuesto->idtipo_puesto = $data["idtipo_puesto"];
                $cupoPuesto->idreemplazo_cupo = $idreemplazo_cupo;
                $cupoPuesto->cupo = $data["cupo"];
                $cupoPuesto->eventual = $data["eventual"];
                $cupoPuesto->totalagentesefector = $data["totalAgentesEfector"];
                $cupoPuesto->totalagentesservicio = $data["totalAgentesServicio"];
                $cupoPuesto->puesto = $data["puesto"];
                $cupoPuesto->created_by = Auth()->user()->idusuario;
                $cupoPuesto->updated_by = Auth()->user()->idusuario;
                $cupoPuesto->updateOrFail();
            }
        }
    }

    /**
     * Actualizar cupo verificando que no supere cupos restantes
     * @param int $idreemplazo_cupo_puesto
     * @param int $cupo_puesto
     */
    public static function actualizarCupoPuesto(int $idreemplazo_cupo_puesto, $cupo_puesto)
    {
        $cupoPuesto = ReemplazoCupoPuesto::where('idreemplazo_cupo_puesto', $idreemplazo_cupo_puesto)->first();
        $acualizarCupoRestantes = reemplazocupo::where('idreemplazo_cupo', $cupoPuesto->idreemplazo_cupo)->first();
        $cupoRestantes = (intval($acualizarCupoRestantes->cupo_restantes) + intval($cupoPuesto->cupo)) - intval($cupo_puesto);

        // verifico si se puede actualizar de acuerdo a los cupos restantes
        if ($cupoRestantes > 0 && $acualizarCupoRestantes->cupo_totales >= $cupoRestantes) {
            $acualizarCupoRestantes->cupo_restantes = $cupoRestantes;
            $acualizarCupoRestantes->usuario = Auth()->user()->nombreusuario;
            // $acualizarCupoRestantes->created_by = $cupoPuesto->created_by;
            $acualizarCupoRestantes->updated_by = Auth()->user()->idusuario;
            $acualizarCupoRestantes->updateOrFail();
        }
        // $verificacion = ReemplazoCupo::actualizarCupoRestantes(cupo: $cupo_puesto, id: $cupoPuesto->idreemplazo_cupo);
        if ($acualizarCupoRestantes) {
            $newCupoPuesto = ReemplazoCupoPuesto::newReemplazoCupoPuestoVigente($cupoPuesto, $cupo_puesto);

            // actualizo estado ree cupo puesto
            $cupoPuesto->estado = false;
            $cupoPuesto->updateOrFail();

            ReemplazoCupoPuesto::actualizarCuposRestantesMismoEfector($acualizarCupoRestantes->idefector, $cupoRestantes);

            return ["cupo_restantes" => $cupoRestantes, "status" => true];
        }

        return ["error" => "No se puede asignar más cupos que los cupos totales.", "status" => false];
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

    public static function newReemplazoCupoPuestoVigente(ReemplazoCupoPuesto $reemplazo, int $cupo) {
            $idusuario = Auth()->user()->idusuario;
            $newCupoPuesto = new ReemplazoCupoPuesto();
            $newCupoPuesto->idservicio = $reemplazo["idservicio"];
            $newCupoPuesto->idtipo_puesto = $reemplazo["idtipo_puesto"];
            $newCupoPuesto->idreemplazo_cupo = $reemplazo->idreemplazo_cupo;
            $newCupoPuesto->idperiodo = $reemplazo->idperiodo;
            $newCupoPuesto->cupo = $cupo;
            $newCupoPuesto->eventual = $reemplazo["eventual"];
            $newCupoPuesto->totalagentesefector = $reemplazo["totalagentesefector"];
            $newCupoPuesto->totalagentesservicio = $reemplazo["totalagentesservicio"];
            $newCupoPuesto->puesto = $reemplazo["puesto"];
            $newCupoPuesto->observaciones = $reemplazo->observaciones;
            $newCupoPuesto->old_idreemplazo_cupo = $reemplazo->old_idreemplazo_cupo;
            $newCupoPuesto->created_by = $reemplazo->created_by;
            $newCupoPuesto->updated_by = $idusuario ?? Auth()->user()->idusuario;
            $newCupoPuesto->created_at = $reemplazo->created_at;
            $newCupoPuesto->updated_at = date('Y-m-d H:i:s');
            $newCupoPuesto->saveOrFail();

        if ($newCupoPuesto) {
            $cupoPuesto = ReemplazoCupoPuesto::where('idreemplazo_cupo_puesto', $reemplazo->idreemplazo_cupo_puesto)->first();
            $cupoPuesto->estado = false;
            $cupoPuesto->update();
        }

        return $newCupoPuesto;
    }

    public static function getDetailPuesto(int $id)
    {
        $detail = DB::table('reemplazo_cupo_puesto as rcp')
                        ->join('reemplazo_cupo as rc', 'rcp.idreemplazo_cupo', "rc.idreemplazo_cupo")
                        ->join('periodo as p', 'rc.idperiodo', 'p.idperiodo')
                        ->join('dependencia as d', 'rcp.idservicio', 'd.iddependencia')
                        ->join('dependencia as e', 'rc.idefector', 'e.iddependencia')
                        ->select(
                            "rcp.*", "rc.cupo_max as cupoEfector", "e.dependencia as efector", 
                            "d.dependencia as servicio", "p.periodo", "rcp.observaciones", 
                            "rc.idefector", "rc.cupo_restantes" 
                        )
                        ->where('rcp.idreemplazo_cupo_puesto', $id)
                        ->first();

        return $detail;
    }

    public static function getInfoCupoServicio(int $idservicio)
    {
        $infoCupoDependencia = DB::table('reemplazo_cupo_puesto as rcp')
                        ->join('reemplazo_cupo as rc', 'rcp.idreemplazo_cupo', "rc.idreemplazo_cupo")
                        ->join('periodo as p', 'rc.idperiodo', 'p.idperiodo')
                        ->join('dependencia as d', 'rcp.idservicio', 'd.iddependencia')
                        ->select(
                            "rcp.*", "d.dependencia as servicio", "d.iddependencia as idservicio", 
                            "p.periodo", "p.idperiodo"
                        )
                        ->where('d.iddependencia', $idservicio)
                        ->distinct("rcp.idreemplazo_cupo")
                        // ->groupBy("rcp.idreemplazo_cupo")
                        ->orderBy("rcp.idreemplazo_cupo", "desc")
                        ->get();

        return $infoCupoDependencia;
    }

    /**
     * Control para la asignacion de cupos que no supere la mitad + 1
     * @param int $totalAgentesServicio
     * @param int $totalCupoAsignar
     * @return bool
     */
    public static function controlCupoReemplazo(int $totalAgentes, int $totalCupoAsignar): bool
    {
        // $cupo = (($totalAgentes/2) + 1);
        $totalCupo = 0;
        if ($totalAgentes === 1) {
            $totalCupo = $totalAgentes * 30;
        }
        else {
            $cupo = ($totalAgentes/2);
            $totalCupo = is_int($cupo) ? $cupo * 30 : (floor($cupo) * 30);
        }
     
        return ($totalCupo >= $totalCupoAsignar);
    }

    /**
     * Actualizar eventaul de un puesto
     * @param id reemplazo_cupo
     *
     */
    public static function actualizarEventualPuesto(int $idreemplazo): ReemplazoCupoPuesto
    {
        $reemplazo_puesto = ReemplazoCupoPuesto::where('idreemplazo_cupo_puesto', $idreemplazo)->first();
        $reemplazo_puesto->eventual = !$reemplazo_puesto->eventual;
        $reemplazo_puesto->updateOrFail();
        
        return $reemplazo_puesto;
    }

    /**
     * Obtener el cupo de creacion de un efector
     * @return string cupo
     */
    public function getCupoEfectorCreacion() : string {
        if ($this->old_idreemplazo_cupo !== null) {
            $cupo = ReemplazoCupo::where('estado', '!=', true)
                        ->select('cupo_max')
                        ->where('idreemplazo_cupo', $this->old_idreemplazo_cupo)
                        ->first();
            return $cupo->cupo_max;
        }
        else {
            $cupo = ReemplazoCupo::select('cupo_max')
                                    ->where('estado', true)
                                    ->where('idreemplazo_cupo', $this->idreemplazo_cupo)
                                    ->first();
            return $cupo->cupo_max;
        }
    }

    /**
     * Obtener el cupo de creacion de un puesto
     * @return string cupo
     */
    public function getCupoPuestoCreacion() : string {
        if (strtotime($this->created_at) === strtotime($this->updated_at)) {
            $cupo = ReemplazoCupoPuesto::select('cupo')
                        ->where('estado', false)
                        ->orWhere('estado', true)
                        ->where('idreemplazo_cupo', $this->idreemplazo_cupo)
                        ->first();
            return $cupo->cupo;
        }
        else {
            $cupo = ReemplazoCupoPuesto::select('cupo')
                                    ->where('estado', false)
                                    ->where('idreemplazo_cupo', $this->idreemplazo_cupo)
                                    ->first();
            return $cupo->cupo;
        }
    }

    /**
     * Obtener cupos que no son eventuales
     */
    public static function getReemplazosCupoPuestoSinEventual() {
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        $perActual = $meses[date('n') - 1] . '/' . date('Y');
        $perAnterior = $meses[date('n') - 2] . '/' . date('Y');
        $periodo = Periodo::orderByDesc('idperiodo')
                            ->select('idperiodo')
                            ->where('periodo', $perActual)->first();

        $ree = ReemplazoCupoPuesto::with(['periodo'])
                        ->whereHas('periodo', function ($que) use ($perActual, $perAnterior) {
                            $que->where('periodo', $perActual)
                                ->orWhere('periodo', $perAnterior)
                                ->select('idperiodo');
                        })
                        ->where('estado', true)
                        ->where('eventual', '!=', true)
                        ->get();                     

        // return ReemplazoCupoPuesto::where('estado', true)
        //                     ->where('eventual','!=', true)
        //                     ->where('idperiodo', $periodo->idperiodo)
        //                     ->get(); 
        
        return $ree;
    }

    /**
     * create reemplazo cupo replica
     */
    public static function createCupoPuestoReplica($reemplazo_cupo_puesto)
    {
        foreach ($reemplazo_cupo_puesto as $data) {
            $idusuario = Auth()->user()->idusuario;
            
            $reemplazo_cupo = ReemplazoCupo::where('old_idreemplazo_cupo', $data->idreemplazo_cupo)->first();

            $cupoPuesto = new ReemplazoCupoPuesto();
            $cupoPuesto->idservicio = $data["idservicio"];
            $cupoPuesto->idtipo_puesto = $data["idtipo_puesto"];
            $cupoPuesto->idreemplazo_cupo = $reemplazo_cupo->idreemplazo_cupo;
            $cupoPuesto->idperiodo = $reemplazo_cupo->idperiodo;
            $cupoPuesto->old_idreemplazo_cupo = $reemplazo_cupo->old_idreemplazo_cupo;
            $cupoPuesto->cupo = $data["cupo"];
            $cupoPuesto->eventual = $data["eventual"];
            $cupoPuesto->totalagentesefector = $data["totalagentesefector"];
            $cupoPuesto->totalagentesservicio = $data["totalagentesservicio"];
            $cupoPuesto->puesto = $data["puesto"];
            $cupoPuesto->observaciones = $data["observaciones"];
            $cupoPuesto->created_by = $idusuario ?? Auth()->user()->idusuario;
            $cupoPuesto->updated_by = $idusuario ?? Auth()->user()->idusuario;
            $cupoPuesto->saveOrFail();
        }
    }

/**
   * Obtener reemplazo cupos
   */
  public static function getReemplazoCupo() {
    $result = ReemplazoCupoPuesto::all();
    if (count($result) > 0) {
        return $result;
    }
    return [];
  }
}
