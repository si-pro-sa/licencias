<?php

namespace App\Repositories;

use App\Models\GrupoFamiliar;
use App\Repositories\BaseRepository;

/**
 * Class GrupoFamiliarRepository
 * @package App\Repositories
 * @version September 22, 2019, 8:56 pm -03
 *
 * @method GrupoFamiliar findWithoutFail($id, $columns = ['*'])
 * @method GrupoFamiliar find($id, $columns = ['*'])
 * @method GrupoFamiliar first($columns = ['*'])
*/
class GrupoFamiliarRepository extends BaseRepository
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
        return GrupoFamiliar::class;
    }
}
