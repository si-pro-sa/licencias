<?php

namespace App\Repositories;

use App\Models\Capacitacion;
use App\Repositories\BaseRepository;

/**
 * Class CapacitacionRepository
 * @package App\Repositories
 * @version September 13, 2021, 4:43 am UTC
*/

class CapacitacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'resolucion',
        'razon',
        'evento_nombre',
        'evento_lugar',
        'fecha_evento_inicio',
        'fecha_evento_final',
        'idCaracter',
        'idTipoEvento',
        'idAlcanceCapacitacion'
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
        return Capacitacion::class;
    }
}
