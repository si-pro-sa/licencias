<?php

namespace App\Repositories;

use App\Models\TipoHorario;
use App\Repositories\BaseRepository;

/**
 * Class TipoHorarioRepository
 * @package App\Repositories
 * @version September 30, 2020, 3:39 pm -03
*/

class TipoHorarioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tipohorario'
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
        return TipoHorario::class;
    }
}
