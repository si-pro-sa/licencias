<?php

namespace App\Repositories;

use App\Models\EvaluacionPsicotecnicaGrupo;
use App\Repositories\BaseRepository;

/**
 * Class EvaluacionPsicotecnicaGrupoRepository
 * @package App\Repositories
 * @version February 3, 2020, 3:57 pm UTC
*/

class EvaluacionPsicotecnicaGrupoRepository extends BaseRepository
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
        return EvaluacionPsicotecnicaGrupo::class;
    }
}
