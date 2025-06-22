<?php

namespace App\Repositories;

use App\Models\CostoLaboral;
use App\Repositories\BaseRepository;

/**
 * Class CostoLaboralRepository
 * @package App\Repositories
 * @version October 20, 2020, 11:32 am -03
*/

class CostoLaboralRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fdesde',
        'fhasta',
        'monto_a',
        'monto_b',
        'monto_c',
        'monto_d',
        'monto_e',
        'monto_f',
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
        return CostoLaboral::class;
    }
}
