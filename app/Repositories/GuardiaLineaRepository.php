<?php

namespace App\Repositories;

use App\Models\GuardiaLinea;
use App\Repositories\BaseRepository;

/**
 * Class GuardiaLineaRepository
 * @package App\Repositories
 * @version March 8, 2020, 8:39 pm -03
*/

class GuardiaLineaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idguardia',
        'hora_desde',
        'hora_hasta',
        'idpuesto',
        'idguardia_tipo_estado_linea',
        'created_by',
        'updated_by',
        'deleted_by',
        'aprobado'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return GuardiaLinea::class;
    }
    
    public function getGuardiasAgente(int $idagente) : array
    {
        $guardias = $this->model
        ->with('guardia')
        ->with('guardia.tipoGuardia')
        ->with('guardia.tipoCampania')
        ->with('guardia.efector')
        ->with('guardia.servicio')
        ->whereHas('puesto', function ($query) use ($idagente) {
            $query->where('idagente', $idagente);
        })
        ->whereIn('idguardia_tipo_estado_linea', [1, 3, 4])
        ->orderByDesc('idguardia_linea')
        ->limit(25)
        ->get();

        if (isset($guardias)) {
            $datos = [];
            foreach ($guardias as $linea) {
                $datos[] = [
                    'idguardia' => $linea->idguardia,
                    'id' => $linea->idguardia_linea,
                    'fecha' => $linea->guardia->fecha->format('d/m/y'),
                    'tipo_guardia' => $linea->guardia->tipoGuardia->tipoguardia,
                    'tipo_campania' => $linea->guardia->tipoCampania->tipocampania,
                    'efector' => $linea->guardia->efector->dependencia,
                    'servicio' => $linea->guardia->servicio->dependencia,
                    'hora_desde' => $linea->hora_desde,
                    'hora_hasta' => $linea->hora_hasta
            ];
            }
            return $datos;
        }
    }
}
