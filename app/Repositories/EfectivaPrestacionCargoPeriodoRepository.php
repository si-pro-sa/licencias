<?php

namespace App\Repositories;

use App\Models\EfectivaPrestacionCargoPeriodo;
use App\Repositories\BaseRepository;

/**
 * Class EfectivaPrestacionCargoPeriodoRepository
 * @package App\Repositories
 * @version April 15, 2020, 10:19 am -03
*/

class EfectivaPrestacionCargoPeriodoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha_desde',
        'fecha_hasta'
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
        return EfectivaPrestacionCargoPeriodo::class;
    }
}
