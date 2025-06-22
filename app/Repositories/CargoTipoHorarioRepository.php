<?php

namespace App\Repositories;

use App\Models\CargoTipoHorario;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class CargoTipoHorarioRepository
 * @package App\Repositories
 * @version May 20, 2019, 10:12 am -03
 *
 * @method CargoTipoHorario findWithoutFail($id, $columns = ['*'])
 * @method CargoTipoHorario find($id, $columns = ['*'])
 * @method CargoTipoHorario first($columns = ['*'])
*/
class CargoTipoHorarioRepository extends BaseRepository
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
        return CargoTipoHorario::class;
    }

    private $tipohorario;
    public function tipohorario($tipohorario){
        if ($tipohorario != null){
            $this->tipohorario = $tipohorario;
            $this->tipohorario = DB::raw("LOWER('%".$tipohorario."%')");
            $this->scopeQuery(function($query){
                return $query->where(DB::raw('LOWER(tipohorario)'),'like',$this->tipohorario);
            });
        }
    }
}
