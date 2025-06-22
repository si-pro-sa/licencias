<?php

namespace App\Repositories;

use App\Models\TipoCargo;
use App\Repositories\BaseRepository;

/**
 * Class TipoCargoRepository
 * @package App\Repositories
 * @version May 11, 2020, 10:50 am -03
*/

class TipoCargoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tipocargo',
        'tipocargo_corto'
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
        return TipoCargo::class;
    }
}
