<?php

namespace App\Repositories;

use App\Models\CargoTipoCese;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;

/**
 * Class CargoTipoCeseRepository
 * @package App\Repositories
 * @version January 12, 2019, 7:37 am UTC
 *
 * @method CargoTipoCese findWithoutFail($id, $columns = ['*'])
 * @method CargoTipoCese find($id, $columns = ['*'])
 * @method CargoTipoCese first($columns = ['*'])
*/
class CargoTipoCeseRepository extends BaseRepository
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
        return CargoTipoCese::class;
    }

    public function toArray()
    {
        return $this->all()
            ->map(function ($model) {
                return [
                    'id' => $model->idcargo_tipo_cese,
                    'tipoCargo' => $model->tipoCargo->tipocargo,
                    'tipoCese' => $model->tipoCese->tipocese,
                    'agenteReemplazado' => ($model->agente_reemplazado ? 'SI' : 'NO')
                ];
            });
    }

    /**
     * Transform model into an array.
     *
     * @return array
     */
    public function findToArray(int $id): array
    {
        $model = $this->find($id);
        return [
            'idcargo_tipo_cese' => $model->idcargo_tipo_cese,
            'idtipo_cese' => $model->idtipo_cese,
            'idtipo_cargo' => $model->idtipo_cargo,
            'agente_reemplazado' => $model->agente_reemplazado,
            'tipoCese' => $model->tipoCese->tipocese,
            'tipoCargo' => $model->tipoCargo->tipocargo,
            'agenteReemplazado' => ($model->agente_reemplazado ? 'SI' : 'NO')
        ];
    }
}
