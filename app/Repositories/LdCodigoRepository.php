<?php

namespace App\Repositories;

use App\Models\LdCodigo;
use App\Repositories\BaseRepository;

/**
 * Class LdCodigoRepository
 * @package App\Repositories
 * @version March 8, 2020, 8:01 pm -03
*/

class LdCodigoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'ldcodigo',
        'horas_semanales',
        'importe',
        'idtipo_nivel',
        'idtipo_agrupamiento',
        'idld_tipo_alta',
        'idtipo_funcion_jerarquica',
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
        return LdCodigo::class;
    }
}
