<?php

namespace App\Repositories;

use App\Models\ReferenteDependencia;
use App\Repositories\BaseRepository;

/**
 * Class ReferenteDependenciaRepository
 * @package App\Repositories
 * @version December 9, 2018, 2:21 pm UTC
 *
 * @method ReferenteDependencia findWithoutFail($id, $columns = ['*'])
 * @method ReferenteDependencia find($id, $columns = ['*'])
 * @method ReferenteDependencia first($columns = ['*'])
*/
class ReferenteDependenciaRepository extends BaseRepository
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
        return ReferenteDependencia::class;
    }
}
