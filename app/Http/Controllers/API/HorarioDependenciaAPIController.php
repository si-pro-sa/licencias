<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateHorarioDependenciaRequest;
use App\Repositories\HorarioDependenciaRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\HorarioDependencia;
use Response;

class HorarioDependenciaAPIController extends AppBaseController
{
    /** @var  HorarioDependenciaRepository */
    private $horarioDependenciaRepository;

    public function __construct(HorarioDependenciaRepository $horarioDependenciaRepo)
    {
        $this->horarioDependenciaRepository = $horarioDependenciaRepo;
    }

    /**
     * Muestro información de Dependencia
     * Horario, cantidad de agentes por función en horario o no
     *
     * @return JSON
     */
    public function getHorarioAtencion(int $iddependencia)
    {
        //Busco funciones asociadas. Si no existe grupo, busco para la función aislada
        $horarios = HorarioDependencia::where('iddependencia', $iddependencia)->get();

        $horario = [];
        if (isset($horarios) && count($horarios) === 1 && ($horarios[0]->idtipo_dia === 8 || $horarios[0]->idtipo_dia === 9 || $horarios[0]->idtipo_dia === 10)) {
            $horario['id'] = $horarios[0]->idhorario_dependencia;
            $horario['tipoHorario'] = $horarios[0]->idtipo_dia === 8 ? 'lv' : ($horarios[0]->idtipo_dia === 10 ? 'ld' : 'rot');
            $horario['hora_desde'] = $horarios[0]->hora_desde;
            $horario['hora_hasta'] = $horarios[0]->hora_hasta;
        } elseif (isset($horarios) && count($horarios) > 1) {
            $horario['tipoHorario'] = 'p';
            $horario['id'] = '';
            $horario['hora_desde'] = '';
            $horario['hora_hasta'] = '';
            $horario['dias'] = [];
            foreach ($horarios as $horarioActual) {
                if (in_array($horarioActual->idtipo_dia, [1, 2, 3, 4, 5, 6, 7])) {
                    $horario['dias'][$horarioActual->idtipo_dia - 1] = [
                        'id' => $horarioActual->idhorario_dependencia,
                        'isChecked' => true,
                        'hora_desde' => $horarioActual->hora_desde,
                        'hora_hasta' => $horarioActual->hora_hasta,
                    ];
                }
            }

            for ($i = 0; $i < 7; $i++) {
                if (!isset($horario['dias'][$i])) {
                    $horario['dias'][$i] = [
                        'id' => '',
                        'isChecked' => false,
                        'hora_desde' => '',
                        'hora_hasta' => '',
                    ];
                }
            }
        } else {
            return $this->sendError('La dependencia no posee horarios cargados');
        }

        return $this->sendResponse($horario, 'Horario de Dependencia');
    }

    /**
     * Store a newly created HorarioDependencia in storage.
     *
     * @param CreateHorarioDependenciaRequest $request
     *
     * @return Response
     */
    public function store(CreateHorarioDependenciaRequest $request)
    {
        $input = $request->all();

        $horarioDependencia = $this->horarioDependenciaRepository->create($input);

        return $this->sendResponse($horarioDependencia, 'El Horario de la Dependencia fue creado correctamente.');
    }
}
