<?php

namespace App\Repositories;

use App\Models\EfectivaPrestacionCargo;
use App\Repositories\BaseRepository;

/**
 * Class EfectivaPrestacionCargoRepository
 * @package App\Repositories
 * @version April 16, 2020, 1:30 pm -03
*/

class EfectivaPrestacionCargoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idcargo',
        'idcargo_tipo_visado_ep',
        'dias',
        'periodos'
        'created_by',
        'updated_by',
        'deleted_by'
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
        return EfectivaPrestacionCargo::class;
    }
}
