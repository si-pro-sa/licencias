<?php

namespace App\Repositories;

use App\Models\GrupoFuncion;
use App\Repositories\BaseRepository;

/**
 * Class GrupoFuncionRepository
 * @package App\Repositories
 * @version October 28, 2019, 7:48 pm -03
 *
 * @method GrupoFuncion findWithoutFail($id, $columns = ['*'])
 * @method GrupoFuncion find($id, $columns = ['*'])
 * @method GrupoFuncion first($columns = ['*'])
*/
class GrupoFuncionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idtipo_grupo_funcion',
        'idtipo_funcion',
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
        return GrupoFuncion::class;
    }
}
