<?php

namespace App\Repositories;

use App\Models\LdAltaBaja;
use App\Repositories\BaseRepository;

/**
 * Class LdAltaBajaRepository
 * @package App\Repositories
 * @version April 16, 2021, 10:34 am -03
*/

class LdAltaBajaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha_creado',
        'idld_alta',
        'idld_baja',
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
        return LdAltaBaja::class;
    }
}
