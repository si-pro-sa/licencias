<?php

namespace App\Repositories;

use App\Models\TipoReferido;
use App\Repositories\BaseRepository;

/**
 * Class TipoReferidoRepository
 * @package App\Repositories
 * @version January 31, 2020, 1:43 pm UTC
*/

class TipoReferidoRepository extends BaseRepository
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
        return TipoReferido::class;
    }
}
