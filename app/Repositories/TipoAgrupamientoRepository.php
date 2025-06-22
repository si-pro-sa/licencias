<?php

namespace App\Repositories;

use App\Models\TipoAgrupamiento;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class TipoAgrupamientoRepository
 * @package App\Repositories
 * @version April 4, 2019, 2:33 pm -03
 *
 * @method TipoAgrupamiento findWithoutFail($id, $columns = ['*'])
 * @method TipoAgrupamiento find($id, $columns = ['*'])
 * @method TipoAgrupamiento first($columns = ['*'])
*/
class TipoAgrupamientoRepository extends BaseRepository
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
        return TipoAgrupamiento::class;
    }

    public function getTipoAgrupamiento($tipoAgrupamiento){
        $this->applyCriteria();
        $this->applyScope();

        $raw = DB::raw("CONCAT(idtipo_agrupamiento,tipoagrupamiento) AS label");
        $results = $this->model->tipoAgrupamiento($tipoAgrupamiento)
            ->orderBy('idtipo_agrupamiento')
            ->get(['idtipo_agrupamiento',$raw,'tipoagrupamiento']);

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }
}
