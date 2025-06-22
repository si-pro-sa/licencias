<?php

namespace App\Repositories;

use App\Models\Feriado;
use App\Repositories\BaseRepository;

/**
 * Class FeriadoRepository
 * @package App\Repositories
 * @version October 7, 2020, 5:59 pm -03
*/

class FeriadoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha',
        'descripcion',
        'idperiodo',
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
        return Feriado::class;
    }
}
