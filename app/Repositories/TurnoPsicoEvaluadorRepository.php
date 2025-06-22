<?php

namespace App\Repositories;

use App\Models\TurnoPsicoEvaluador;
use App\Repositories\BaseRepository;

/**
 * Class TurnoPsicoEvaluadorRepository
 * @package App\Repositories
 * @version December 27, 2019, 3:31 pm UTC
*/

class TurnoPsicoEvaluadorRepository extends BaseRepository
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
        return TurnoPsicoEvaluador::class;
    }
}
