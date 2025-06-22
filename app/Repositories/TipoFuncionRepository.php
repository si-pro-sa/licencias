<?php

namespace App\Repositories;

use App\Models\TipoFuncion;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class TipoFuncionRepository
 * @package App\Repositories
 * @version February 8, 2019, 7:09 pm -03
 *
 * @method TipoFuncion findWithoutFail($id, $columns = ['*'])
 * @method TipoFuncion find($id, $columns = ['*'])
 * @method TipoFuncion first($columns = ['*'])
*/
class TipoFuncionRepository extends BaseRepository
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
        return TipoFuncion::class;
    }

    public function getTipoFuncion($tipofuncion){
        $this->applyCriteria();
        $this->applyScope();

        $raw = DB::raw("CONCAT(idtipo_funcion,' - ',tipofuncion) as label");
        $results = $this->model->tipoFuncion($tipofuncion)
            ->orderBy('idtipo_funcion')
            ->get(['idtipo_funcion',$raw,'tipofuncion']);

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }
}
