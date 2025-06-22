<?php

namespace App\Repositories;

use App\Models\HorarioPuestoHistorico;
use App\Repositories\BaseRepository;

/**
 * Class HorarioPuestoHistoricoRepository
 * @package App\Repositories
 * @version October 5, 2020, 10:08 pm -03
*/

class HorarioPuestoHistoricoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idpuesto',
        'idtipo_horario',
        'dias_guardia',
        'hora_desde',
        'hora_hasta',
        'usuario',
        'operacion',
        'foperacion'
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
        return HorarioPuestoHistorico::class;
    }
}
