<?php

namespace App\Repositories;

use App\Models\ExtendidoEvaluacionPsicotecnica;
use App\Repositories\BaseRepository;

/**
 * Class ExtendidoEvaluacionPsicotecnicaRepository
 * @package App\Repositories
 * @version January 13, 2020, 3:27 pm UTC
*/

class ExtendidoEvaluacionPsicotecnicaRepository extends BaseRepository
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
        return ExtendidoEvaluacionPsicotecnica::class;
    }
}
