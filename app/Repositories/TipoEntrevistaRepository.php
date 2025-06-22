<?php

namespace App\Repositories;

use App\Models\TipoEntrevista;
use App\Repositories\BaseRepository;

/**
 * Class TipoEntrevistaRepository
 * @package App\Repositories
 * @version January 9, 2020, 5:36 am UTC
*/

class TipoEntrevistaRepository extends BaseRepository
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
        return TipoEntrevista::class;
    }
}
