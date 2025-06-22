<?php

namespace App\Repositories;

use App\Models\CargoTipoFuncion;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class CargoTipoFuncionRepository
 * @package App\Repositories
 * @version May 8, 2019, 7:59 am -03
 *
 * @method CargoTipoFuncion findWithoutFail($id, $columns = ['*'])
 * @method CargoTipoFuncion find($id, $columns = ['*'])
 * @method CargoTipoFuncion first($columns = ['*'])
*/
class CargoTipoFuncionRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
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
        return CargoTipoFuncion::class;
    }

    public function searchTipoFuncion($tipoFuncion){
        $this->applyCriteria();
        $this->applyScope();

        $raw = DB::raw("CONCAT(idcargo_tipo_funcion,cargotipo_funcion) AS label");
        $results = $this->model->tipoFuncion($tipoFuncion)
            ->orderBy('cargotipo_funcion')
            ->get(['idcargo_tipo_funcion',$raw,'cargotipo_funcion']);

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }
}
