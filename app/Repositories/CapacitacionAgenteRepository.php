<?php

namespace App\Repositories;

use App\Models\CapacitacionAgente;
use App\Repositories\BaseRepository;

/**
 * Class CapacitacionAgenteRepository
 * @package App\Repositories
 * @version September 14, 2021, 5:26 pm UTC
*/

class CapacitacionAgenteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idCapacitacion',
        'idAgente'
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
        return CapacitacionAgente::class;
    }
}
