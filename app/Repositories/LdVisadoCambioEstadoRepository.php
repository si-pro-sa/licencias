<?php

namespace App\Repositories;

use App\Models\LdVisadoCambioEstado;
use App\Repositories\BaseRepository;

/**
 * Class LdVisadoCambioEstadoRepository
 * @package App\Repositories
 * @version January 29, 2021, 11:08 am -03
*/

class LdVisadoCambioEstadoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idld_cambio_estado',
        'usuario',
        'operacion',
        'foperacion'
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
        return LdVisadoCambioEstado::class;
    }
}
