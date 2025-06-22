<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTipoEspecialidadAPIRequest;
use App\Http\Requests\API\UpdateTipoEspecialidadAPIRequest;
use App\Models\TipoEspecialidad;
use App\Repositories\TipoEspecialidadRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class TipoEspecialidadController
 * @package App\Http\Controllers\API
 */

class TipoEspecialidadAPIController extends AppBaseController
{
    /**
     * Muestro listado de tipos de agrupamiento utilizando json
     *
     * @return JSON
     */
    public function vueSelect()
    {
        $tiposEspecialidad = TipoEspecialidad::orderBy('tipoespecialidad')->get()->makeHidden(['usuario', 'foperacion', 'operacion'])->map(function ($model) {
            return ['value' => $model->idtipo_especialidad, 'label' => $model->tipoespecialidad];
        });
        return $tiposEspecialidad;
    }
}
