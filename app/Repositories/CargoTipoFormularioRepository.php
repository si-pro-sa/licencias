<?php

namespace App\Repositories;

use App\Models\CargoTipoFormulario;
use App\Repositories\BaseRepository;

/**
 * Class CargoTipoFormularioRepository
 * @package App\Repositories
 * @version December 12, 2019, 9:39 am UTC
*/

class CargoTipoFormularioRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cargotipo_formulario'
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
        return CargoTipoFormulario::class;
    }
}
