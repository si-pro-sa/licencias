<?php

namespace App\Repositories;

use App\Models\LdCambioEstado;
use App\Repositories\BaseRepository;

/**
 * Class LdCambioEstadoRepository
 * @package App\Repositories
 * @version March 8, 2020, 8:01 pm -03
*/

class LdCambioEstadoRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'fecha_creado',
        'fhasta',
        'fuera_termino',
        'usada',
        'info_adicional',
        'idefector',
        'idperiodo',
        'idld_estado',
        'idld_tipo_cambio_estado',
        'idld_alta',
        'idtipo_formulario',
        'usuario',
        'operacion',
        'foperacion',
        'bloqueado'
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
        return LdCambioEstado::class;
    }

    public function setFechaInicioContinuidades()
    {
        $continuidades = $this->model
        ->with('ldAlta')
        // ->select(['fdesde', 'fhasta', 'idld_alta'])
        ->where('idld_tipo_cambio_estado', 1)
        ->where('fdesde', null)
        ->get();
        
        foreach ($continuidades as $continuidad) {
            $continuidad->fdesde = $continuidad->getFechaInicioContinuidad();
            $continuidad->save();
        }

        return true;
    }
}
