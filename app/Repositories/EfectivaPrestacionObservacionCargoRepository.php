<?php

namespace App\Repositories;

use App\Models\EfectivaPrestacionObservacionCargo;
use App\Repositories\BaseRepository;

/**
 * Class EfectivaPrestacionObservacionCargoRepository
 * @package App\Repositories
 * @version April 16, 2020, 1:34 pm -03
*/

class EfectivaPrestacionObservacionCargoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idefectiva_prestacion_cargo',
        'idtipo_observacion_novedad',
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
        return EfectivaPrestacionObservacionCargo::class;
    }
}
