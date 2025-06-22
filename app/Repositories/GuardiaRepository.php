<?php
namespace App\Repositories;

use App\Models\Guardia;
use App\Repositories\BaseRepository;

/**
 * Class GuardiaRepository
 * @package App\Repositories
 * @version March 8, 2020, 8:39 pm -03
*/

class GuardiaRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha',
        'fuera_termino',
        'cantidad_lv',
        'cantidad_sdf',
        'cantidad_novedad_lv',
        'cantidad_novedad_sdf',
        'idperiodo',
        'idefector',
        'idservicio',
        'idtipo_guardia',
        'idtipo_campania',
        'idtipo_formulario',
        'created_by',
        'updated_by',
        'deleted_by'
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
        return Guardia::class;
    }
}
