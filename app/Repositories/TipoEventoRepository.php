<?php

namespace App\Repositories;

use App\Models\TipoEvento;
use App\Repositories\BaseRepository;

/**
 * Class TipoEventoRepository
 * @package App\Repositories
 * @version September 13, 2021, 4:42 am UTC
*/

class TipoEventoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'codigo',
        'descripcion'
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
        return TipoEvento::class;
    }
}
