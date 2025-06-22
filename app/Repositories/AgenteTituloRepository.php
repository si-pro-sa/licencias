<?php

namespace App\Repositories;

use App\Models\AgenteTitulo;
use App\Repositories\BaseRepository;

/**
 * Class AgenteTituloRepository
 * @package App\Repositories
 * @version December 18, 2020, 10:08 am -03
*/

class AgenteTituloRepository extends BaseRepository
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
        return AgenteTitulo::class;
    }
}
