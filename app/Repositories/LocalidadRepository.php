<?php

namespace App\Repositories;

use App\Models\Localidad;
use App\Repositories\BaseRepository;

/**
 * Class LocalidadRepository
 * @package App\Repositories
 * @version February 17, 2020, 3:05 am UTC
*/

class LocalidadRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [

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
        return Localidad::class;
    }
}
