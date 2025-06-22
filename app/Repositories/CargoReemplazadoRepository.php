<?php

namespace App\Repositories;

use App\Models\CargoReemplazado;
use App\Repositories\BaseRepository;

/**
 * Class CargoReemplazadoRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:07 pm -03
 *
 * @method CargoReemplazado findWithoutFail($id, $columns = ['*'])
 * @method CargoReemplazado find($id, $columns = ['*'])
 * @method CargoReemplazado first($columns = ['*'])
*/
class CargoReemplazadoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idcargo',
        'resolucion_ministerial',
        'idpuesto',
        'idtipo_funcion',
        'idtipo_nivel',
        'idtipo_agrupamiento',
        'idagentetitulo',
        'idtipo_especialidad',
        'idtipo_cese',
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
        return CargoReemplazado::class;
    }
}
