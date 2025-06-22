<?php

namespace App\Repositories;

use App\Models\EfectivaPrestacionReemplazo;
use App\Repositories\BaseRepository;

/**
 * Class EfectivaPrestacionReemplazoRepository
 * @package App\Repositories
 * @version March 8, 2021, 11:33 am -03
*/

class EfectivaPrestacionReemplazoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'total_dias',
        'costo_total',
        'idefector',
        'primera_vez',
        'idperiodo',
        'idreemplazo',
        'usuario',
        'foperacion',
        'operacion',
        'enviada',
        'idservicio',
        'observado'
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
        return EfectivaPrestacionReemplazo::class;
    }
}
