<?php

namespace App\Repositories;

use App\Models\Caracter;
use App\Repositories\BaseRepository;

/**
 * Class CaracterRepository
 * @package App\Repositories
 * @version September 13, 2021, 4:40 am UTC
*/

class CaracterRepository extends BaseRepository
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
        return Caracter::class;
    }
}
