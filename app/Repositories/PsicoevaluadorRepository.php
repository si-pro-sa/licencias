<?php

namespace App\Repositories;

use App\Models\PsicoEvaluador;
use App\Repositories\BaseRepository;

/**
 * Class PsicoevaluadorRepository
 * @package App\Repositories
 * @version January 24, 2020, 1:55 pm UTC
*/

class PsicoevaluadorRepository extends BaseRepository
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
        return PsicoEvaluador::class;
    }
}
