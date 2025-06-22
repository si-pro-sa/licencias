<?php

namespace App\Repositories;

use App\Models\TipoGrupoFuncion;
use App\Repositories\BaseRepository;

/**
 * Class TipoGrupoFuncionRepository
 * @package App\Repositories
 * @version October 28, 2019, 7:46 pm -03
 *
 * @method TipoGrupoFuncion findWithoutFail($id, $columns = ['*'])
 * @method TipoGrupoFuncion find($id, $columns = ['*'])
 * @method TipoGrupoFuncion first($columns = ['*'])
*/
class TipoGrupoFuncionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tipogrupo_funcion',
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
        return TipoGrupoFuncion::class;
    }
}
