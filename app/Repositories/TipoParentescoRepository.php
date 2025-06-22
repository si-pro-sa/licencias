<?php

namespace App\Repositories;

use App\Models\TipoParentesco;
use App\Repositories\BaseRepository;

/**
 * Class TipoParentescoRepository
 * @package App\Repositories
 * @version December 7, 2019, 2:10 am UTC
*/

class TipoParentescoRepository extends BaseRepository
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
        return TipoParentesco::class;
    }
}
