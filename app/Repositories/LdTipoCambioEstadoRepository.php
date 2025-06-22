<?php

namespace App\Repositories;

use App\Models\LdTipoCambioEstado;
use App\Repositories\BaseRepository;

/**
 * Class LdTipoCambioEstadoRepository
 * @package App\Repositories
 * @version January 29, 2021, 7:23 pm -03
*/

class LdTipoCambioEstadoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ldtipo_cambio_estado',
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
        return LdTipoCambioEstado::class;
    }
}
