<?php

namespace App\Repositories;

use App\Models\Licencia;
use App\Repositories\BaseRepository;

/**
 * Class LicenciaRepository
 * @package App\Repositories
 * @version September 22, 2019, 11:05 pm -03
 *
 * @method Licencia findWithoutFail($id, $columns = ['*'])
 * @method Licencia find($id, $columns = ['*'])
 * @method Licencia first($columns = ['*'])
*/
class LicenciaRepository extends BaseRepository
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
        return Licencia::class;
    }
}
