<?php

namespace App\Repositories;

use App\Models\Periodo;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class PeriodoRepository
 * @package App\Repositories
 * @version April 15, 2019, 12:41 pm -03
 *
 * @method Periodo findWithoutFail($id, $columns = ['*'])
 * @method Periodo find($id, $columns = ['*'])
 * @method Periodo first($columns = ['*'])
*/
class PeriodoRepository extends BaseRepository
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
        return Periodo::class;
    }

    public function getPeriodo($periodo,$min){
        $this->applyCriteria();
        $this->applyScope();

        if ($min == null){
            $raw = DB::raw("CONCAT(idperiodo,' - ',periodo) AS label");
            $results = $this->model->periodo($periodo)
                ->orderBy('fdesde')
                ->get([$raw,'idperiodo', 'periodo','fdesde']);
        }else{
            $raw = DB::raw("CONCAT(idperiodo,' - ',periodo) AS label");
            $results = $this->model->periodo($periodo)
                ->whereDate('fdesde','>=',$min)
                ->orderBy('fdesde')
                ->get([$raw,'idperiodo', 'periodo','fdesde']);
        }

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }
}
