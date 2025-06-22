<?php

namespace App\Repositories;

use App\Models\CargoHorario;
use App\Repositories\BaseRepository;

/**
 * Class CargoHorarioRepository
 * @package App\Repositories
 * @version November 3, 2019, 11:57 pm -03
 *
 * @method CargoHorario findWithoutFail($id, $columns = ['*'])
 * @method CargoHorario find($id, $columns = ['*'])
 * @method CargoHorario first($columns = ['*'])
*/
class CargoHorarioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idcargo',
        'idtipo_dia',
        'idefector',
        'idservicio',
        'hora_desde',
        'hora_hasta'
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
        return CargoHorario::class;
    }
}
