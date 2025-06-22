<?php

namespace App\Repositories;

use App\Models\Dependencia;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class DependenciaRepository
 * @package App\Repositories
 * @version November 28, 2018, 11:33 pm UTC
 *
 * @method Dependencia findWithoutFail($id, $columns = ['*'])
 * @method Dependencia find($id, $columns = ['*'])
 * @method Dependencia first($columns = ['*'])
*/
class DependenciaRepository extends BaseRepository
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
        return Dependencia::class;
    }

    public function getDependencia($dependencia)
    {
        $this->applyCriteria();
        $this->applyScope();

        $raw = DB::raw('CONCAT(codigorrhh,dependencia) AS label');
        $results = $this->model->dependencia($dependencia)
            ->orderBy('codigorrhh')
            ->get(['iddependencia', $raw, 'codigorrhh', 'dependencia']);

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }
}
