<?php

namespace App\Repositories;

use App\Models\CargoDevolucionRelacion;
use App\Repositories\BaseRepository;

/**
 * Class CargoDevolucionRelacionRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:04 pm -03
 *
 * @method CargoDevolucionRelacion findWithoutFail($id, $columns = ['*'])
 * @method CargoDevolucionRelacion find($id, $columns = ['*'])
 * @method CargoDevolucionRelacion first($columns = ['*'])
*/
class CargoDevolucionRelacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idcargo_devuelto',
        'idcargo',
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
        return CargoDevolucionRelacion::class;
    }
}
