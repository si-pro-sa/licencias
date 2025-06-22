<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Class Dependencia.
 *
 * @version November 27, 2018, 7:04 am UTC
 *
 * @property int iddependencia
 * @property string dependencia
 * @property int idtipo_dependencia
 * @property string usuario
 * @property string operacion
 * @property string|Carbon foperacion
 * @property int codigo
 * @property string codigorrhh
 * @property int jerarquia
 * @property bool redservicio
 */
class Dependencia extends Model
{
    protected $table = 'dependencia';
    protected $primaryKey = 'iddependencia';
    public $connection = 'pgsql_public';

    protected $appends = ['area_operativa', 'codigo_nombre'];

    /**
     * Ids de dependencias que son AO (efector)
     * @return array
     */
    public static $ao = [118, 20, 319, 612, 949, 329, 57, 762, 194, 782, 238, 702, 222, 818, 790, 740, 555, 794, 488, 692];

    /**
     * @var
     */
    public $tipodependencia_;

    /**
     * @var
     */
    public $descendencia = [];

    /**
     * @var array
     */
    public $ascendencia = [];

    public $fillable = [
        'dependencia',
        'idtipo_dependencia',
        'usuario',
        'operacion',
        'foperacion',
        'codigo',
        'codigorrhh',
        'jerarquia',
        'redservicio',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'iddependencia' => 'integer',
        'dependencia' => 'string',
        'idtipo_dependencia' => 'integer',
        'usuario' => 'string',
        'operacion' => 'string',
        'codigo' => 'integer',
        'codigorrhh' => 'string',
        'jerarquia' => 'integer',
        'redservicio' => 'boolean',
    ];

    public static $default = [
        'iddependencia' => '',
        'dependencia' => '',
        'idtipo_dependencia' => '',
        'usuario' => '',
        'operacion' => '',
        'codigo' => '',
        'codigorrhh' => '',
        'jerarquia' => '',
        'redservicio' => '',
    ];

    /**
     * Returns código y nombre de dependencia.
     *
     * @return string
     */
    public function tipoDependencia()
    {
        return $this->belongsTo(TipoDependencia::class, 'idtipo_dependencia');
    }

    /**
     * Returns código y nombre de dependencia.
     *
     * @return string
     */
    public function getCodigoNombreAttribute()
    {
        return $this->codigorrhh . ' - ' . $this->dependencia;
    }

    /**
     * Scope a query to only include efectores solo con el nombre.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEfector($query, $keyword, $exacto = false)
    {
        $keyword = strtoupper($keyword);
        if ($exacto) {
            $sign = '=';
            $keywords = $keyword;
        } else {
            $sign = 'LIKE';
            $keywords = "%{$keyword}%";
        }

        return $query
            ->whereRaw("UPPER(dependencia) {$sign} ?", $keywords)
            ->whereNotIn('iddependencia', [1, 2, 3, 4, 5])
            ->orderBy('iddependencia')
            ->whereNotNull('codigorrhh')
            ->first();
    }

    /**
     * Scope a query to only include efectores solo con los Ids.
     */
    public function scopeNombredep($query, $keyword, $exacto = false)
    {
        $keyword = strtoupper($keyword);
        if ($exacto) {
            $sign = '=';
            $keywords = $keyword;
        } else {
            $sign = 'ILIKE';
            $keywords = "%{$keyword}%";
        }
        return $query
            ->whereRaw("UPPER(dependencia) {$sign} ?", $keywords)
            ->whereNotIn('iddependencia', [1, 2, 3, 4, 5])
            //            ->whereIn('jerarquia', [1, 2, 3])
            ->orderBy('iddependencia')
            ->whereNotNull('codigorrhh')
            ->get(['iddependencia']);
    }

    /**
     * Returns Padre o Área Operativa.
     *
     * @return string
     */
    public function getAreaOperativaAttribute()
    {
        return $this->getPadre();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function horarios()
    {
        return $this->hasMany(\App\Models\HorarioDependencia::class, 'iddependencia');
    }

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [];

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSoloEfectores($query)
    {
        $user = auth()->user();
        if (!$user->isRRHH()) {
            return $query
                ->whereIn('iddependencia', $user->getEfectoresVisibles())
                ->orderBy('codigorrhh')
                ->whereNotNull('codigorrhh')
                ->get(['iddependencia', 'dependencia', 'codigorrhh']);
        }

        if (Cache::has('efectores')) {
            return Cache::get('efectores');
        }
        $efectores = $query
            ->where('iddependencia', '<>', 873)
            ->where('codigorrhh', 'not like', '%.%')
            ->orWhere('codigorrhh', '100.1')
            ->orWhere('codigorrhh', '100.2')
            ->orWhere('codigorrhh', '100.3')
            ->orWhere('codigorrhh', '410PCI.0.1')
            ->orWhere('codigorrhh', '410SGA.1.1')
            ->orWhere('codigorrhh', '410DJDC.1')
            // ->whereIn('idtipo_dependencia', [6, 7, 8])
            ->orderBy('codigorrhh')
            ->whereNotNull('codigorrhh')
            ->get(['iddependencia', 'dependencia', 'codigorrhh']);

        Cache::put('efectores', $efectores, now()->addMinutes(10));
        return $efectores;
    }

    public function scopeObtDependenciasSegunUsuario($query, $tiposDependencias)
    {
        $Dependencias = [];
        $user = auth()->user();
        if (!$user->isRRHH()) {
            $iddependencias = [];

            $iddependencias = $user->getEfectoresVisibles();

            foreach ($iddependencias as $iddependencia) {
                $idDependenciaDescentes = (Dependencia::where('iddependencia', $iddependencias)->first())->getIdsDescendencia();
                $iddependencias = $idDependenciaDescentes;
            }

            $Dependencias = $query
                ->whereIn('iddependencia', $iddependencias)
                ->whereIn('idtipo_dependencia', $tiposDependencias)
                ->orderBy('codigorrhh')->get();
            return $Dependencias;
        }


        $dependencias = $query
            ->where('iddependencia', '<>', 873)
            ->whereIn('idtipo_dependencia', $tiposDependencias)
            ->orderBy('codigorrhh')
            ->whereNotNull('codigorrhh')
            ->get(['iddependencia', 'dependencia', 'codigorrhh']);

        return $dependencias;
    }


    public function dependenciasSegunUsuario()
    {
        $Dependencias = [];
        $user = auth()->user();
        if (!$user->isRRHH()) {
            $iddependencias = [];

            $iddependencias = $user->getEfectoresVisibles();

            foreach ($iddependencias as $iddependencia) {
                $idDependenciaDescentes = (Dependencia::where('iddependencia', $iddependencias)->first())->getIdsDescendencia();
                $iddependencias = $idDependenciaDescentes;
            }

            $Dependencias = Dependencia::where('iddependencia', $iddependencias)->orderBy('codigorrhh')->get();
            return $Dependencias;
        }


        $dependencias = Dependencia::where('iddependencia', '<>', 873)
            ->orderBy('codigorrhh')
            ->whereNotNull('codigorrhh')
            ->get(['iddependencia', 'dependencia', 'codigorrhh']);

        return $dependencias;
    }

    /**
     * Scope para mostrar solo dependencias efectoras de la Red de Servicios.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSoloEfectoresRed($query)
    {
        $dependencia = Dependencia::find(191);
        $idsHijas = $dependencia->getIdsDescendencia();

        return $query
            ->orderBy('codigorrhh')
            ->whereIn('iddependencia', $idsHijas)
            ->whereIn('idtipo_dependencia', [6, 7, 8])
            // ->where('iddependencia', '<>', 873)
            ->whereNotNull('codigorrhh')
            ->get(['iddependencia', 'dependencia', 'codigorrhh']);
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHijas($query, $iddependencia)
    {
        $dependenciasHijas = Dependencia::find($iddependencia)->getIdsDescendencia();

        return $query
            ->whereIn('iddependencia', $dependenciasHijas)
            ->whereNotNull('codigorrhh')
            ->orderBy('codigorrhh')
            ->get(['iddependencia', 'dependencia', 'codigorrhh']);
    }

    public function scopeDependencia($query, $dependencia)
    {
        if (trim($dependencia) != '') {
            $raw = DB::raw('LOWER(CONCAT(codigorrhh,dependencia))');
            $query->where($raw, 'like', '%' . strtolower($dependencia) . '%');
        }
    }

    public static function scopeCodigo($query, $dependencia)
    {
        if (trim($dependencia) != '') {
            $raw = DB::raw('LOWER(codigorrhh)');
            $query->where($raw, 'like', '%' . strtolower($dependencia) . '%');
        }
    }

    /**
     * Retorna los id de las dependencias hijas, nietas, bisnietas.
     *
     * @return array
     */
    public function getIdsDescendencia()
    {
        $listadoDependencias = [$this->iddependencia];
        $idHijas = $this->getIdHijas([$this->iddependencia]);
        $listadoDependencias = array_merge($listadoDependencias, $idHijas);

        while (isset($idHijas) && count($this->getIdHijas($idHijas)) > 0) {
            $idHijas = $this->getIdHijas($idHijas);
            $listadoDependencias = array_merge($listadoDependencias, $idHijas);
        }

        return $listadoDependencias;
    }

    /**
     * Retorna los id de las dependencias hijas.
     *
     * @return array
     */
    private function getIdHijas(array $arrayIds)
    {
        $idHijas = [];
        if (!empty($arrayIds)) {
            $idHijas = DependenciaRelacion::where('revisado', true)->whereIn('iddependenciapadre', $arrayIds)->pluck('iddependenciahija')->toArray();
        }

        return $idHijas;
    }

    /**
     * @throws CException
     */
    public function loadAscendencia()
    {
        if (empty($this->ascendencia)) {
            $sql = "WITH RECURSIVE ascendencia AS (
                    SELECT a.iddependenciapadre, a.iddependenciahija, h.dependencia, h.idtipo_dependencia, 9999 AS depth
                    FROM dependenciarelacion a
                    LEFT JOIN dependencia h
                    ON a.iddependenciapadre=h.iddependencia
                    WHERE a.iddependenciahija = {$this->iddependencia} AND a.revisado
                UNION
                    SELECT b.iddependenciapadre, b.iddependenciahija, h.dependencia, h.idtipo_dependencia, depth - 1
                    FROM dependenciarelacion b
                    LEFT JOIN dependencia h
                    ON b.iddependenciapadre=h.iddependencia
                    JOIN ascendencia c
                    ON b.iddependenciahija = c.iddependenciapadre
                    WHERE b.revisado
                )
                SELECT * FROM ascendencia WHERE idtipo_dependencia=6 OR idtipo_dependencia=7 OR idtipo_dependencia=8";

            $this->ascendencia = DB::select($sql);
        }
    }

    public static function scopeCodigoLike($query, $dependencia)
    {
        if (trim($dependencia) != '') {
            $raw = DB::raw('LOWER(codigorrhh)');
            $query->where($raw, 'like', '%' . strtolower($dependencia) . '%');
        }
    }

    public function scopeDependenciaLike($query, $dependencia)
    {
        if (trim($dependencia) != '') {
            $raw = DB::raw('LOWER(CONCAT(dependencia))');
            $query->where($raw, 'like', '%' . strtolower($dependencia) . '%');
        }
    }

    public function getAscendencia(): array
    {
        if (empty($this->ascendencia)) {
            $this->loadAscendencia();
        }

        $idsEfectoresPadre = [];
        foreach ($this->ascendencia as $value) {
            $idsEfectoresPadre = $value->iddependenciapadre;
        }

        return $idsEfectoresPadre;
    }

    /**
     * @throws CException
     */
    public function getPadre(): string
    {
        //Carga las ascendencias
        if ($this->idtipo_dependencia === 6) {
            return $this->dependencia;
        }

        $this->loadAscendencia();
        if (isset($this->ascendencia[0]->dependencia)) {
            return $this->ascendencia[0]->dependencia;
        }

        return 'Sin Efector';
    }

    /**
     * @throws CException
     */
    public function getIdPadre(): int
    {
        $this->loadAscendencia();
        if (isset($this->ascendencia[0]->iddependenciapadre)) {
            return $this->ascendencia[0]->iddependenciapadre;
        }

        return $this->iddependencia;
    }

    /**
     * @throws CException
     */
    public function getIdsPadresCargo(): array
    {
        $this->loadAscendencia();

        $padres = [$this->iddependencia];
        foreach ($this->ascendencia as $padre) {
            if (isset($padre->iddependenciapadre)) {
                array_push($padres, $padre->iddependenciapadre);
            }
        }

        return $padres;
    }

    /**
     * @throws CException
     */
    public function getIdsPadres(): array
    {
        $this->loadAscendencia();
        if (isset($this->ascendencia[0]->iddependenciapadre, $this->ascendencia[1]->iddependenciapadre)) {
            return [$this->ascendencia[0]->iddependenciapadre, $this->ascendencia[1]->iddependenciapadre];
        }

        if (isset($this->ascendencia[0]->iddependenciapadre)) {
            return [$this->ascendencia[0]->iddependenciapadre];
        }

        return [$this->iddependencia];
    }

    public function getCantidadAgentesFuncion(array $funciones, ?int $idtipo_dia = null, ?string $hora_desde = null, ?string $hora_hasta = null)
    {
        if (empty($this->descendencia)) {
            $this->descendencia = $this->getIdsDescendencia();
        }

        if (isset($idtipo_dia, $hora_desde, $hora_hasta)) {
            return Puesto::whereNull('fhasta')
                ->whereIn('idtipo_funcion', $funciones)
                ->whereIn('iddependencia', $this->descendencia)
                ->whereIn('idtipo_planta', Puesto::getPlantasAgentesdePlanta())
                // ->whereIn('idtipo_planta', [1, 2, 6])
                ->whereHas('horarios', function ($query) use ($idtipo_dia, $hora_desde, $hora_hasta) {
                    if (in_array($idtipo_dia, [8, 9, 10])) {
                        $query->where('idtipo_dia', $idtipo_dia)
                            ->where('hora_desde', '>=', $hora_desde)
                            ->where('hora_hasta', '<=', $hora_hasta);
                    } else {
                        $idtipo_dia += 1;
                        $idTipoDia = $idtipo_dia > 10 ? [$idtipo_dia, $idtipo_dia - 10] : [$idtipo_dia, $idtipo_dia + 10];
                        $query->whereIn('idtipo_dia', $idTipoDia)
                            ->where('hora_desde', '>=', $hora_desde)
                            ->where('hora_hasta', '<=', $hora_hasta);
                    }
                })
                ->count();
        }

        return Puesto::whereNull('fhasta')
            ->whereIn('idtipo_funcion', $funciones)
            ->whereIn('idtipo_planta', Puesto::getPlantasAgentesdePlanta())
            ->whereIn('iddependencia', $this->descendencia)
            ->count();
    }

    public function getHorarioAtencion()
    {
        $horarioString = '';
        if (count($this->horarios) === 0) {
            $horarioString = 'No posee horarios cargados';
        } elseif (count($this->horarios) === 1) {
            $horarioString = $this->horarios[0]->tipoDia->tipodia . ' ' . $this->horarios[0]->hora_desde . ' A ' . $this->horarios[0]->hora_hasta;
        } else {
            foreach ($this->horarios as $key => $horario) {
                if ($key === 0) {
                    $horarioString .= $horario->tipoDia->tipodia . ' ' . $horario->hora_desde . ' A ' . $horario->hora_hasta;
                } else {
                    $horarioString .= ' | ' . $horario->tipoDia->tipodia . ' ' . $horario->hora_desde . ' A ' . $horario->hora_hasta;
                }
            }
        }

        return $horarioString;
    }

    public function getCodigoDependenciaAttribute()
    {
        return $this->codigorrhh . ' - ' . $this->dependencia;
    }

    public static function getDotacionDependencias(int $idefector, int $idservicio, int $idtipo_funcion, string $hora_desde, string $hora_hasta, int $idtipo_dia = 999, array $funciones = [])
    {
        $idtipo_dia = $idtipo_dia === 999 ? 1 : $idtipo_dia++;
        //Busco funciones asociadas. Si no existe grupo, busco para la función aislada
        if (isset($funciones) && count($funciones) < 1) {
            $grupoFuncion = GrupoFuncion::with('tipoGrupoFuncion')
                ->where('idtipo_funcion', $idtipo_funcion)
                ->first();

            if (isset($grupoFuncion)) {
                $nombreFuncion = $grupoFuncion->tipoGrupoFuncion->tipogrupo_funcion ?? '';
                $funciones = GrupoFuncion::funcionesGrupo($grupoFuncion->idtipo_grupo_funcion);
            } else {
                $tipoFuncion = TipoFuncion::where('idtipo_funcion', $idtipo_funcion)->first();
                $nombreFuncion = $tipoFuncion->tipofuncion ?? '';
                $funciones = [$idtipo_funcion];
            }
        }

        $dia = TipoDia::find($idtipo_dia);

        //Busco Efector y Servicio
        $efector = Dependencia::with('horarios')
            ->where('iddependencia', $idefector)
            ->first();
        $servicio = Dependencia::with('horarios')
            ->where('iddependencia', $idservicio)
            ->first();

        //Busco Cantidad de Agentes según función y horario del efector y su descendencia
        $cantidadPuestosEfectorHorario = $efector->getCantidadAgentesFuncion($funciones, $idtipo_dia, $hora_desde, $hora_hasta);
        $cantidadTotalPuestosEfector = $efector->getCantidadAgentesFuncion($funciones);

        //Busco Cantidad de Agentes según función y horario del servicio y su descendencia
        $cantidadPuestosServicioHorario = $servicio->getCantidadAgentesFuncion($funciones, $idtipo_dia, $hora_desde, $hora_hasta);
        $cantidadTotalPuestosServicio = $servicio->getCantidadAgentesFuncion($funciones);

        $textoServicio = "";
        if (substr(strtoupper($efector->dependencia), 0, 3) == 'AO ') {
            if ($servicio->idtipo_dependencia < 6) {
                $registro =  Dependencia::where('iddependencia', $servicio->iddependencia_superior)->first();
                if (isset($registro)) {
                    $textoServicio = $registro->dependencia . " - " . $servicio->dependencia;
                };
            };
        } else {
            $textoServicio = $servicio->dependencia;
        };

        return [
            'funcion' => $nombreFuncion ?? '',
            'dia' => $dia->tipodia ?? '',
            'horario' => $hora_desde . ' a ' . $hora_hasta,
            'efector' => [
                'nombre' => $efector->dependencia,
                'diagramacion' => $efector->getHorarioAtencion(),
                'cantidad_horario' => $cantidadPuestosEfectorHorario,
                'cantidad_diferente_horario' => $cantidadTotalPuestosEfector - $cantidadPuestosEfectorHorario,
                'total' => $cantidadTotalPuestosEfector,
            ],
            'servicio' => [
                //                'nombre' => $efector->dependencia . '-' . $servicio->dependencia,
                'nombre' => $efector->dependencia . '-' . $textoServicio,
                'diagramacion' => $servicio->getHorarioAtencion(),
                'cantidad_horario' => $cantidadPuestosServicioHorario,
                'cantidad_diferente_horario' => $cantidadTotalPuestosServicio - $cantidadPuestosServicioHorario,
                'total' => $cantidadTotalPuestosServicio,
            ],
            /*
$textoServicio = "";
if (substr(strtoupper($h->efector->dependencia),0,3)=='AO ')
   {
   if ($h->servicio->idtipo_dependencia<6)
      {
      $registro =  Dependencia::where('iddependencia', $h->servicio->iddependencia_superior)->first();
      if (isset($registro))
         { $textoServicio = $registro->dependencia . " - " . $this->servicio->dependencia; };
      };
   };
*/
        ];
    }

    /**
     * Obtener listado de ids de Areas Operativas.
     * @return \Illuminate\Support\Collection
     */
    public static function getIdsAO(): Collection
    {
        $areasOperativas = DB::table('dependencia as d')
            ->distinct('id_area_operativa')
            ->select('id_area_operativa')
            ->where("id_area_operativa", "!=", 0)->get();

        return $areasOperativas;
    }

    /**
     * Obtener dependencias de una Area Programatica.
     * @return \Illuminate\Http\JsonResponse
     */
    public static function obtenerDependenciasAP(int $idefector, string $cod): Collection
    {
        $dependenciasAP = DB::table('dependencia')
            ->select("*")
            ->where("id_area_programatica", "=", $idefector)
            ->where("id_area_operativa", "=", null)
            ->where('codigorrhh', 'like', DB::raw("'$cod%'"))->get();
        // ->where('codigorrhh', 'ilike', ''. $cod . '%')->get();

        return $dependenciasAP;
    }

    /**
     * Obtener dependencias de una Area Programatica vue select.
     * @return array
     */
    public static function obtenerDependenciasAPSelect(int $idefector, string $cod): array
    {
        $dependenciasAP = DB::table('dependencia')
            ->select("*")
            ->where("id_area_programatica", "=", $idefector)
            ->where("id_area_operativa", "=", null)
            ->where('codigorrhh', 'like', DB::raw("'$cod%'"))->get();

        $select = [];
        foreach ($dependenciasAP as $ap) {
            array_push($select, ["label" => $ap->dependencia, "value" => $ap->iddependencia]);
        }
        return $select;
    }

    /**
     * Obtener listado de ids de Areas Programaticas.
     * @return \Illuminate\Support\Collection
     */
    public static function getIdsAP(): Collection
    {
        $areasProgramaticas = DB::table('dependencia as d')
            ->distinct('id_area_programatica')
            ->select('id_area_programatica')
            ->where("id_area_programatica", "!=", 0)
            ->where("id_area_programatica", "!=", 1114)->get();

        return $areasProgramaticas;
    }

    public function format(): array
    {
        return [
            'value' => $this->iddependencia,
            'label' => $this->codigorrhh . ' - ' . $this->dependencia,
        ];
    }

    /**
     * Obtener ids de dependencias red de servicio
     * @param int $iddependencia
     * @return Collection
     */
    public static function getIdsRedServicio(int $iddependencia): Collection
    {
        $redServicio = DB::table('dependencia')
            ->select('iddependencia')
            ->where('iddependencia', $iddependencia)
            // ->where('iddependencia', 1114)
            ->get();
        return $redServicio;
    }

    /**
     * Obtener listado de dependencias red de servicio
     * @param int $iddependencia
     * @param string $cod
     * @return Collection
     */
    public static function getDependenciasRedServicio(int $iddependencia, string $cod): Collection
    {
        $dependenciasRedServicio = DB::table('dependencia')
            ->select('*')
            // ->where('iddependencia_superior', $iddependencia)
            // ->orWhere('iddependencia_superior', 1114)
            ->where('codigorrhh', 'like', DB::raw("'$cod%'"))->get();

        return $dependenciasRedServicio;
    }

    /**
     * obtener dependencias red servicio vueselect
     * @param int $iddependencia
     * @param string $cod
     * @return array 
     */
    public static function getDependenciasRedServicioSelect(int $iddependencia, string $cod): array
    {
        $dependenciasRedServicio = DB::table('dependencia')
            ->select('iddependencia', 'dependencia')
            // ->where('iddependencia_superior', $iddependencia)
            ->where('codigorrhh', 'like', DB::raw("'$cod%'"))->get();

        $dependenciasRed = [];
        foreach ($dependenciasRedServicio as $red) {
            array_push($dependenciasRed, ['label' => $red->dependencia, 'value' => $red->iddependencia]);
        }

        return $dependenciasRed;
    }

    /**
     * obtener puestos area operativa
     * @param int $iddependencia
     * @return array 
     */
    public static function getPuestosAO(int $iddependencia): array
    {
        $puestosAO = DB::table('puesto as p')
            ->join('dependencia as d', 'p.iddependencia', 'd.iddependencia')
            ->join('tipo_puesto as tp', 'p.idtipo_puesto', 'tp.idtipo_puesto')
            ->select('p.idtipo_puesto', 'tp.tipo_puesto', 'd.id_area_operativa')
            ->distinct()
            ->where('d.id_area_operativa', $iddependencia)->get()->toArray();

        foreach ($puestosAO as $key => $value) {
            $totalAgentes = Dependencia::getCantidadAgentesTipoPuesto(iddependencia: $iddependencia, puesto: $puestosAO[$key]->idtipo_puesto);
            $puestosAO[$key]->totalAgentesEfector = $totalAgentes["efector"];
            $puestosAO[$key]->puesto = $puestosAO[$key]->tipo_puesto;
            $puestosAO[$key]->idservicio = $iddependencia;
            $puestosAO[$key]->eventual = false;
            $puestosAO[$key]->observaciones = '';
            $puestosAO[$key]->cupo = $puestosAO[$key]->cupo ?? '';
            $puestosAO[$key]->totalAgentesServicio = $totalAgentes["servicio"];
        }

        return $puestosAO;
    }

    public static function getCantidadAgentesTipoPuesto(int $iddependencia, int $puesto)
    {
        $totalAgentesEfector = 0;
        $totalAgentesServicio = 0;
        $dependenciaSuperior = Dependencia::select("iddependencia_superior")
            ->where('iddependencia', $iddependencia)
            ->first();
        $idsRed = [1114, 191];

        $listAO = [];
        $areasOperativas = Dependencia::getIdsAO();

        foreach ($areasOperativas as $key => $value) {
            array_push($listAO, $areasOperativas[$key]->id_area_operativa);
        }

        if (in_array($dependenciaSuperior->iddependencia_superior, $idsRed)) {
            $totalAgentesEfector =  Puesto::whereNull('fhasta')
                ->join('dependencia', 'puesto.iddependencia', 'dependencia.iddependencia')
                // ->where('idtipo_puesto', $puesto)
                ->whereIn('idtipo_planta', [1, 2, 6])
                // ->where('dependencia.iddependencia_superior', $dependenciaSuperior->iddependencia_superior)
                ->where('dependencia.id_area_operativa', $iddependencia)
                ->count();
            $totalAgentesServicio = Puesto::whereNull('puesto.fhasta')
                ->join('dependencia', 'puesto.iddependencia', 'dependencia.iddependencia')
                ->where('puesto.idtipo_puesto', $puesto)
                ->whereIn('puesto.idtipo_planta', [1, 2, 6])
                ->where('dependencia.id_area_operativa', $iddependencia)
                // ->orWhere('dependencia.iddependencia', $iddependencia)
                // ->where('puesto.iddependencia', $iddependencia)
                ->count();

            return ["efector" => $totalAgentesEfector, "servicio" => $totalAgentesServicio];
        }

        $totalAgentesEfector =  Puesto::whereNull('fhasta')
            ->join('dependencia', 'puesto.iddependencia', 'dependencia.iddependencia')
            ->where('idtipo_puesto', $puesto)
            // ->whereIn('idtipo_planta', Puesto::getPlantasAgentesdePlanta())
            ->whereIn('idtipo_planta', [1, 2, 6])
            ->where('dependencia.iddependencia_superior', $dependenciaSuperior->iddependencia_superior)
            ->count();

        $totalAgentesServicio = Puesto::whereNull('puesto.fhasta')
            ->join('dependencia', 'puesto.iddependencia', 'dependencia.iddependencia')
            ->where('puesto.idtipo_puesto', $puesto)
            ->whereIn('puesto.idtipo_planta', [1, 2, 6])
            ->where('dependencia.iddependencia', $iddependencia)
            ->where('dependencia.iddependencia_superior', $dependenciaSuperior->iddependencia_superior)
            ->count();
        return ["efector" => $totalAgentesEfector, "servicio" => $totalAgentesServicio];
    }

    public static function getCantidadAgentesTipoPuestoAO(int $iddependencia, int $puesto)
    {
        // $dependenciasHijas = Dependencia::find($iddependencia)->getIdsDescendencia();
        $totalAgentesEfector = 0;
        $totalAgentesServicio = 0;
        $dependenciaSuperior = Dependencia::select("iddependencia_superior")->where('iddependencia', $iddependencia)->first();

        $listAO = [];
        $areasOperativas = Dependencia::getIdsAO();

        foreach ($areasOperativas as $key => $value) {
            array_push($listAO, $areasOperativas[$key]->id_area_operativa);
        }

        if (in_array($iddependencia, $listAO)) {
            $totalAgentesEfector =  Puesto::whereNull('fhasta')
                ->join('dependencia', 'puesto.iddependencia', 'dependencia.iddependencia')
                ->where('idtipo_puesto', $puesto)
                // ->whereIn('idtipo_planta', Puesto::getPlantasAgentesdePlanta())
                ->whereIn('idtipo_planta', [1, 2, 6])
                ->where('dependencia.iddependencia_superior', $dependenciaSuperior->iddependencia_superior)
                ->count();

            $totalAgentesServicio = Puesto::whereNull('fhasta')
                ->join('dependencia', 'puesto.iddependencia', 'dependencia.iddependencia')
                ->where('idtipo_puesto', $puesto)
                // ->whereIn('idtipo_planta', Puesto::getPlantasAgentesdePlanta())
                ->whereIn('idtipo_planta', [1, 2, 6])
                ->where('dependencia.iddependencia_superior', $dependenciaSuperior->iddependencia_superior)
                ->count();

            return ["efector" => $totalAgentesEfector, "servicio" => $totalAgentesServicio];
        }
    }

    /**
     * Saber si la dependencia es una Areas Operativas.
     * @return true
     */
    public static function isAO(int $idefector): bool
    {
        $listAO = [];
        $areasOperativas = Dependencia::getIdsAO();

        foreach ($areasOperativas as $key => $value) {
            array_push($listAO, $areasOperativas[$key]->id_area_operativa);
        }

        return in_array($idefector, $listAO);
    }

    /**
     * Saber si la dependencia es una Area Programatica.
     * @return true
     */
    public static function isAP(int $idefector): bool
    {
        $listAP = [];
        $areasProgramaticas = Dependencia::getIdsAP();

        foreach ($areasProgramaticas as $key => $value) {
            array_push($listAP, $areasProgramaticas[$key]->id_area_programatica);
        }

        return in_array($idefector, $listAP);
    }

    /**
     * Obtener efectores sin descendencias AO
     * @param int $idefector
     * @return 
     */
    public static function selectEfectores()
    {
        $listAO = [];
        $listAP = [];
        $areasOperativas = Dependencia::getIdsAO();
        $areasProgramaticas = Dependencia::getIdsAP();

        foreach ($areasOperativas as $key => $value) {
            array_push($listAO, $areasOperativas[$key]->id_area_operativa);
        }

        foreach ($areasProgramaticas as $key => $value) {
            array_push($listAP, $areasProgramaticas[$key]->id_area_programatica);
        }

        return DB::table('dependencia')
            ->where('iddependencia', '<>', 873)
            ->where('codigorrhh', 'not like', '%.%')
            ->whereNull('id_area_operativa')
            ->whereNull('id_area_programatica')
            ->orWhereIn('iddependencia', $listAO)
            ->orWhereIn('iddependencia', $listAP)
            ->orWhere('codigorrhh', '100.1')
            ->orWhere('codigorrhh', '100.2')
            ->orWhere('codigorrhh', '100.3')
            ->orWhere('codigorrhh', '615')
            ->where('codigorrhh', '!=', '615E.0.1')
            ->orWhere('codigorrhh', '410PCI.0.1')
            ->orWhere('codigorrhh', '410SGA.1.1')
            ->orWhere('codigorrhh', '410DJDC.1')
            ->orderBy('codigorrhh')
            ->whereNotNull('codigorrhh')
            ->get(['iddependencia', 'dependencia', 'codigorrhh']);
    }


    public function isRedServicio(): bool
    {
        if (isset($this->redservicio)) {
            return $this->redservicio;
        }
        foreach ($this->ascendencia as $dependencia) {
            if ($dependencia->iddependenciapadre === 191) {
                //191 - DIRECCION GENERAL DE RED DE SERVICIOS
                return true;
            }
        }

        return false;
    }
}
