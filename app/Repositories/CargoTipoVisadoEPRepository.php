<?php

namespace App\Repositories;

use App\Models\CargoTipoVisadoEP;
use App\Repositories\BaseRepository;

/**
 * Class CargoTipoVisadoEPRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:05 pm -03
 *
 * @method CargoTipoVisadoEP findWithoutFail($id, $columns = ['*'])
 * @method CargoTipoVisadoEP find($id, $columns = ['*'])
 * @method CargoTipoVisadoEP first($columns = ['*'])
*/
class CargoTipoVisadoEPRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cargo_tipo_visado_ep'
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
        return CargoTipoVisadoEP::class;
    }
}
