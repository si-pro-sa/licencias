<?php

namespace App\Repositories;

use App\Models\PersonaParentesco;
use App\Repositories\BaseRepository;

/**
 * Class PersonaParentescoRepository
 * @package App\Repositories
 * @version December 7, 2019, 2:13 am UTC
*/

class PersonaParentescoRepository extends BaseRepository
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
        return PersonaParentesco::class;
    }
}
