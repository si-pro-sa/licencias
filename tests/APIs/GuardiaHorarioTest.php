<?php

namespace Tests\APIs;

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;

use App\User;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\HorarioPuesto;
use App\Models\Puesto;
use App\Models\PuestoAdicional;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class GuardiaHorarioTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware;

    protected $payload = null;
    protected $created = false;

    public function setUp(): void
    {
        parent::setUp();

        //Creo horario de dependencia
        //Crear Horario Efector y Servicio
        $horarioDependenciaPayload = [
            'id' => null,
            'idefector' => 880,
            'idservicio' => 880,
            'tipoHorario' => 'lv',
            'hora_desde' => '00:00',
            'hora_hasta' => '00:00',
            'dias' =>  [
                ['id' => null, 'nombre' => 'Lunes', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Martes', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Miércoles', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Jueves', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Viernes', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Sábado', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Domingo', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
            ]
        ];
        $this->json('POST', '/api/horario-dependencia', $horarioDependenciaPayload);
        $horarioDependenciaPayload['idservicio'] = 991;
        $this->json('POST', '/api/horario-dependencia', $horarioDependenciaPayload);

        //HORARIO DEFAULT PARA CARGOS
        $this->payload = $this->defaultValuesHorarioPuesto();
        $this->resetDias();
    }

    /*
    * NO CREA GUARDIAS
    */

    /* PERSONALIZADO */

    //Guardia día de semana -> horario personalizado
    /** @test */
    public function guardia_dia_semana_en_horario_puesto_personalizado()
    {
        //Usuario
        $lmema = User::find(19);
        //Puesto activo GIMENA BUFFO, MARIA DELFINA 38249513
        $idPuestoActivo = 34841;

        PuestoAdicional::where('idpuesto', $idPuestoActivo)->delete();
        HorarioPuesto::where('puesto_id', $idPuestoActivo)->delete();
        $puesto = Puesto::where('idpuesto', $idPuestoActivo)->first();
        //Le seteo una nueva función para testear
        $puesto->idtipo_funcion = 498;
        $puesto->save();
        //Crear Puesto Adicional
        $puestoAdicionalPayload = [
            'idpuesto' => $idPuestoActivo,
            'idefector' => 452,
            'iddependencia' => 1420
        ];
        $response = $this->actingAs($lmema)->json('POST', '/api/agente/puestos-adicionales', $puestoAdicionalPayload);

        $puestoAdicional = PuestoAdicional::where('idpuesto', $idPuestoActivo)->first();
        //Crear Horario de Agente
        $this->addEfector();
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['puesto_id'] = $idPuestoActivo;
        $this->payload['efectores'][0]['idpuesto'] = $idPuestoActivo;
        $this->payload['efectores'][0]['puesto_type'] = 'App\Models\Puesto';
        $this->payload['efectores'][0]['idefector'] = 452;
        $this->payload['efectores'][0]['idservicio'] = 1420;
        $this->payload['efectores'][0]['tipoHorario'] = 'lv';
        $this->payload['efectores'][0]['hora_desde'] = '08:00';
        $this->payload['efectores'][0]['hora_hasta'] = '17:00';
        //Segundo Efector
        $this->payload['efectores'][1]['idefector'] = 452;
        $this->payload['efectores'][1]['idservicio'] = 1420;
        $this->payload['efectores'][1]['puesto_id'] = $puestoAdicional->idpuesto_adicional;
        $this->payload['efectores'][1]['idpuesto'] = $idPuestoActivo;
        $this->payload['efectores'][1]['puesto_type'] = 'App\Models\PuestoAdicional';
        $this->payload['efectores'][1]['tipoHorario'] = 'pg';
        $this->payload['efectores'][1]['dias'][1] = ['id' => null, 'nombre' => 'Martes', 'hora_desde' => '17:00', 'hora_hasta' => '05:00',  'isChecked' => true];
        $this->payload['efectores'][1]['dias'][3] = ['id' => null, 'nombre' => 'Jueves','hora_desde' => '17:00', 'hora_hasta' => '05:00',  'isChecked' => true];

        $response = $this->actingAs($lmema)->json('POST', '/api/horario-puesto', $this->payload);

        //Verificar interposición horaria
        //Guardia Martes
        $response = $this->actingAs($lmema)->json('GET', "/api/horario/existe-interposicion-horaria/{$idPuestoActivo}/2021-05-03/16:00/04:00");

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'data' => true,
            'message' => 'Horario Interpuesto'
        ]);

        //Guardia Miércoles
        $response = $this->actingAs($lmema)->json('GET', "/api/horario/existe-interposicion-horaria/{$idPuestoActivo}/2021-05-04/16:00/04:00");

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'data' => true,
            'message' => 'Horario Interpuesto'
        ]);

        //Guardia Miércoles
        $response = $this->actingAs($lmema)->json('GET', "/api/horario/existe-interposicion-horaria/{$idPuestoActivo}/2021-05-06/16:00/04:00");

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'data' => true,
            'message' => 'Horario Interpuesto'
        ]);
    }

    // GUARDIAS PARA GIMENA BUFFO, MARIA DELFINA
    /** @test */
    // public function guardia_dia_semana_en_horario_puesto_lav_y_adicional_personalizado()
    // {
    //     //Usuario
    //     $lmema = User::find(19);

    //     //Puesto activo GIMENA BUFFO, MARIA DELFINA 38249513
    //     $idPuestoActivo = 51229;
    //     PuestoAdicional::where('idpuesto', $idPuestoActivo)->delete();
    //     HorarioPuesto::where('puesto_id', $idPuestoActivo)->delete();
        
        
    //     //Crear Puesto Adicional
    //     $puestoAdicionalPayload = [
    //         'idpuesto' => $idPuestoActivo,
    //         'idefector' => 452,
    //         'iddependencia' => 1419
    //     ];

    //     $response = $this->actingAs($lmema)->json('POST', '/api/agente/puestos-adicionales', $puestoAdicionalPayload);
    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => true,
    //         'data' => true,
    //     ]);

    //     $puestoAdicional = PuestoAdicional::where('idpuesto', $idPuestoActivo)->first();

    //     //Crear Horario de Agente
    //     $this->addEfector();
    //     $this->payload['puesto_id'] = $idPuestoActivo;
    //     $this->payload['puesto_type'] = 'App\Models\Puesto';
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idefector'] = 452;
    //     $this->payload['efectores'][0]['idservicio'] = 1419;
    //     $this->payload['efectores'][0]['puesto_type'] = 'App\Models\Puesto';
    //     $this->payload['efectores'][0]['puesto_type'] = $idPuestoActivo;
    //     $this->payload['efectores'][0]['idpuesto'] = $idPuestoActivo;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '08:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '17:00';
    //     //Segundo Efector
    //     $this->payload['efectores'][1]['idefector'] = 452;
    //     $this->payload['efectores'][1]['idservicio'] = 1419;
    //     $this->payload['efectores'][1]['idpuesto'] = $idPuestoActivo;
    //     $this->payload['efectores'][1]['puesto_type'] = 'App\Models\PuestoAdicional';
    //     $this->payload['efectores'][1]['puesto_id'] = $puestoAdicional->idpuesto_adicional;
    //     $this->payload['efectores'][1]['tipoHorario'] = 'pg';
    //     $this->payload['efectores'][1]['dias'][1] = ['id' => null, 'nombre' => 'Martes', 'hora_desde' => '16:00', 'hora_hasta' => '04:00',  'isChecked' => true];
    //     $this->payload['efectores'][1]['dias'][3] = ['id' => null, 'nombre' => 'Jueves','hora_desde' => '21:00', 'hora_hasta' => '09:00',  'isChecked' => true];

    //     $response = $this->actingAs($lmema)->json('POST', '/api/horario-puesto', $this->payload);
    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => true,
    //         'data' => true,
    //     ]);
    //     //Verificar interposición horaria
    //     $response = $this->actingAs($lmema)->json('GET', "/api/horario/existe-interposicion-horaria/{$idPuestoActivo}/2020-10-06/08:00/20:00");
    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => true,
    //         'data' => false,
    //         'message' => 'Horario Interpuesto'
    //     ]);
    // }

    private function defaultValuesHorarioPuesto()
    {
        return [
            'id' => null,
            'documento' => "33762078",
            'efectores' => [
                [
                    'idefector' => 452,
                    'idservicio' => null,
                    'diagramacionHabitual' => [
                        'efector' => null,
                        'servicio' => null,
                    ],
                    'agentes' => [
                        'efector' => [
                            'mismo_horario' => 0,
                            'diferente_horario' => 0,
                            'total' => 0,
                        ],
                        'servicio' => [
                            'mismo_horario' => 0,
                            'diferente_horario' => 0,
                            'total' => 0,
                        ],
                    ],
                    'tipoHorario' => 'p',
                    'hora_desde' => '',
                    'hora_hasta' => '',
                    'cantidad_mensual' => 0,
                    'dias_seleccionados' => 0,
                ]
            ]
        ];
    }

    private function createHorarioPuesto($idefector, $idservicio, $tipoHorario, $hora_desde, $hora_hasta, $dias = null): void
    {
        $payload = [
            'id' => null,
            'idefector' => $idefector,
            'idservicio' => $idservicio,
            'tipoHorario' => $tipoHorario,
            'hora_desde' => $hora_desde,
            'hora_hasta' => $hora_hasta,
            'dias' => $dias
        ];

        if (!$dias) {
            $payload['dias'] = [
                ['id' => null, 'nombre' => 'Lunes', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Martes', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Miércoles', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Jueves', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Viernes', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Sábado', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Domingo', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
            ];
        }
        $this->json('POST', '/api/horario-dependencia', $payload);
    }

    private function resetDias()
    {
        foreach ($this->payload['efectores'] as $key => $efector) {
            $this->payload['efectores'][$key]['dias'] = [
                ['id' => null, 'nombre' => 'Miércoles', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Miércoles', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Miércoles', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
                ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
            ];
        }
    }

    private function addEfector()
    {
        $this->payload['efectores'][] = [
            'idefector' => 452,
            'idservicio' => null,
            'diagramacionHabitual' => [
                'efector' => null,
                'servicio' => null,
            ],
            'agentes' => [
                'efector' => [
                    'mismo_horario' => 0,
                    'diferente_horario' => 0,
                    'total' => 0,
                ],
                'servicio' => [
                    'mismo_horario' => 0,
                    'diferente_horario' => 0,
                    'total' => 0,
                ],
            ],
            'tipoHorario' => 'p',
            'hora_desde' => '',
            'hora_hasta' => '',
            'cantidad_mensual' => 0,
            'dias_seleccionados' => null,
        ];
    }
}
