<?php

namespace App\Repositories;

use App\Models\CargoCambioEstadoObservacion;
use App\Repositories\BaseRepository;

/**
 * Class CargoCambioEstadoObservacionRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:06 pm -03
 *
 * @method CargoCambioEstadoObservacion findWithoutFail($id, $columns = ['*'])
 * @method CargoCambioEstadoObservacion find($id, $columns = ['*'])
 * @method CargoCambioEstadoObservacion first($columns = ['*'])
*/
class CargoCambioEstadoObservacionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idcargo_cambio_estado',
        'idcargo_tipo_observacion',
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
        return CargoCambioEstadoObservacion::class;
    }
}
