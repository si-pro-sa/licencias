<?php

namespace App\Repositories;

use App\Models\TipoRecomendacion;
use App\Repositories\BaseRepository;

/**
 * Class TipoRecomendacionRepository
 * @package App\Repositories
 * @version January 7, 2020, 3:01 pm UTC
*/

class TipoRecomendacionRepository extends BaseRepository
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
        return TipoRecomendacion::class;
    }
}
