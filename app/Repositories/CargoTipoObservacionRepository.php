<?php

namespace App\Repositories;

use App\Models\CargoTipoObservacion;
use App\Repositories\BaseRepository;

/**
 * Class CargoTipoObservacionRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:06 pm -03
 *
 * @method CargoTipoObservacion findWithoutFail($id, $columns = ['*'])
 * @method CargoTipoObservacion find($id, $columns = ['*'])
 * @method CargoTipoObservacion first($columns = ['*'])
*/
class CargoTipoObservacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cargotipo_observacion'
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
        return CargoTipoObservacion::class;
    }
}
