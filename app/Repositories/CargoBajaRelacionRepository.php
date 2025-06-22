<?php

namespace App\Repositories;

use App\Models\CargoBajaRelacion;
use App\Repositories\BaseRepository;

/**
 * Class CargoBajaRelacionRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:03 pm -03
 *
 * @method CargoBajaRelacion findWithoutFail($id, $columns = ['*'])
 * @method CargoBajaRelacion find($id, $columns = ['*'])
 * @method CargoBajaRelacion first($columns = ['*'])
*/
class CargoBajaRelacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idcargo_baja',
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
        return CargoBajaRelacion::class;
    }
}
