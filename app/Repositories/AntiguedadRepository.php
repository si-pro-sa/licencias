<?php

namespace App\Repositories;

use App\Models\Antiguedad;
use App\Repositories\BaseRepository;

/**
 * Class AntiguedadRepository
 * @package App\Repositories
 * @version January 13, 2020, 2:17 am UTC
*/

class AntiguedadRepository extends BaseRepository
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
        return Antiguedad::class;
    }
}
