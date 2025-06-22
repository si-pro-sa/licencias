<?php

namespace App\Repositories;

use App\Models\TipoSolicitud;
use App\Repositories\BaseRepository;

/**
 * Class TipoSolicitudRepository
 * @package App\Repositories
 * @version October 20, 2020, 9:54 am -03
*/

class TipoSolicitudRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'tiposolicitud',
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
        return TipoSolicitud::class;
    }
}
