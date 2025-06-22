<?php

namespace App\Repositories;

use App\Models\CargoTipoVisado;
use App\Repositories\BaseRepository;

/**
 * Class CargoTipoVisadoRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:05 pm -03
 *
 * @method CargoTipoVisado findWithoutFail($id, $columns = ['*'])
 * @method CargoTipoVisado find($id, $columns = ['*'])
 * @method CargoTipoVisado first($columns = ['*'])
*/
class CargoTipoVisadoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cargotipo_visado'
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
        return CargoTipoVisado::class;
    }
}
