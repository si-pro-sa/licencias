<?php

namespace App\Repositories;

use App\Models\TipoCese;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class TipoCeseRepository
 * @package App\Repositories
 * @version January 12, 2019, 7:37 am UTC
 *
 * @method TipoCese findWithoutFail($id, $columns = ['*'])
 * @method TipoCese find($id, $columns = ['*'])
 * @method TipoCese first($columns = ['*'])
*/
class TipoCeseRepository extends BaseRepository
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
        return TipoCese::class;
    }

    public function getTipoCese($tipocese){
        $this->applyCriteria();
        $this->applyScope();

        $raw = DB::raw("CONCAT(idtipo_cese,' - ',tipocese) AS label");
        $results = $this->model->tipocese($tipocese)
            ->orderBy('idtipo_cese')
            ->get([$raw,'idtipo_cese', 'tipocese']);

        $this->resetModel();
        $this->resetScope();

        return $this->parserResult($results);
    }
}
