<?php

namespace App\Repositories;

use App\Models\CargoDiasGuardia;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class CargoDiasGuardiaRepository
 * @package App\Repositories
 * @version May 22, 2019, 7:17 am -03
 *
 * @method CargoDiasGuardia findWithoutFail($id, $columns = ['*'])
 * @method CargoDiasGuardia find($id, $columns = ['*'])
 * @method CargoDiasGuardia first($columns = ['*'])
*/
class CargoDiasGuardiaRepository extends BaseRepository
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
        return CargoDiasGuardia::class;
    }
    private $cargodiasguardia;
    public function cargodiasguardia($cargodiasguardia){
        if ($cargodiasguardia != null){
            $this->cargodiasguardia = $cargodiasguardia;
            $this->cargodiasguardia = DB::raw("LOWER('%".$cargodiasguardia."%')");
            $this->scopeQuery(function($query){
                return $query->where(DB::raw('LOWER(cargodiasguardia)'),'like',$this->cargodiasguardia);
            });
        }
    }
}
