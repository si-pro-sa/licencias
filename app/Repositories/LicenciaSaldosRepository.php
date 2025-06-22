<?php

namespace App\Repositories;

use App\Models\LicenciaSaldos;
use App\Repositories\BaseRepository;

/**
 * Class LicenciaSaldosRepository
 * @package App\Repositories
 * @version March 7, 2020, 2:21 pm UTC
*/

class LicenciaSaldosRepository extends BaseRepository
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
        return LicenciaSaldos::class;
    }
}
