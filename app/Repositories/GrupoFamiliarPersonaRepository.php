<?php

namespace App\Repositories;

use App\Models\GrupoFamiliarPersona;
use App\Repositories\BaseRepository;

/**
 * Class GrupoFamiliarPersonaRepository
 * @package App\Repositories
 * @version September 22, 2019, 9:37 pm -03
 *
 * @method GrupoFamiliarPersona findWithoutFail($id, $columns = ['*'])
 * @method GrupoFamiliarPersona find($id, $columns = ['*'])
 * @method GrupoFamiliarPersona first($columns = ['*'])
*/
class GrupoFamiliarPersonaRepository extends BaseRepository
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
        return GrupoFamiliarPersona::class;
    }
}
