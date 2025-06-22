<?php

namespace App\Repositories;

use App\Models\EvaluacionTecnica;
use App\Repositories\BaseRepository;

/**
 * Class EvaluacionTecnicaRepository
 * @package App\Repositories
 * @version January 22, 2020, 1:34 pm UTC
*/

class EvaluacionTecnicaRepository extends BaseRepository
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
        return EvaluacionTecnica::class;
    }
}
