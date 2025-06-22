<?php

namespace App\Repositories;

use App\Models\PuestoAdicional;
use App\Repositories\BaseRepository;

/**
 * Class PuestoAdicionalRepository
 * @package App\Repositories
 * @version October 28, 2019, 8:09 pm -03
 *
 * @method PuestoAdicional findWithoutFail($id, $columns = ['*'])
 * @method PuestoAdicional find($id, $columns = ['*'])
 * @method PuestoAdicional first($columns = ['*'])
*/
class PuestoAdicionalRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'idpuesto',
        'iddependencia',
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
        return PuestoAdicional::class;
    }

    public function getPuestosAdicionales(int $idpuesto) : array
    {
        $puestoAdicionales = $this->model
        ->with('dependencia')
        ->where('idpuesto', $idpuesto)
        ->get();

        if (isset($puestoAdicionales)) {
            foreach ($puestoAdicionales as $pa) {
                $puestos[] = [
                    'idpuesto_adicional' => $pa->idpuesto_adicional,
                    'efector' => $pa->dependencia->getPadre(),
                    'servicio' => $pa->dependencia->codigo_nombre,
                    'horarios' => ($pa->horarios()->count() > 0 ? 'Tiene' : 'No tiene'),
                ];
            }
            return $puestos ?? [];
        }
        return [];
    }

    public function create($inputs)
    {
        $puestoAdicional = new PuestoAdicional();
        $puestoAdicional->idpuesto = $inputs['idpuesto'];
        $puestoAdicional->iddependencia = $inputs['iddependencia'];

        if ($puestoAdicional->save()) {
            return true;
        }
        return false;
    }
}
