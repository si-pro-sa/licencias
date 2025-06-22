<?php

namespace App\Repositories;

use App\Models\AlcanceCapacitacion;
use App\Repositories\BaseRepository;

/**
 * Class AlcanceCapacitacionRepository
 * @package App\Repositories
 * @version September 13, 2021, 4:43 am UTC
*/

class AlcanceCapacitacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'descripcion'
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
        return AlcanceCapacitacion::class;
    }
}
