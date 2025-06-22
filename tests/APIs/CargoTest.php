<?php

namespace Tests\APIs;

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;

use App\Models\HorarioDependencia;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\User;

class CargoTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware;

    protected $payload = null;

    public function setUp(): void
    {
        parent::setUp();

        $horariosABorrar = [1408, 1409, 1410, 1411, 1412, 1413, 1414, 1415, 1416, 1417, 1689, 1688, 440, 1355];
        HorarioDependencia::whereIn('iddependencia', $horariosABorrar)->delete();
        $this->createAllDependencias();

        //HORARIO DEFAULT PARA CARGOS
        $this->payload = $this->defaultValuesCargo();
        $this->resetDias();
    }

    /**
     * Reset the migrations
     */
    public function tearDown() : void
    {
        // $this->artisan('migrate:reset');
        parent::tearDown();
    }

    private function createAllDependencias(): void
    {
        //Efector MATERNIDAD Servicio UNIDAD NEO I
        //LUNES A DOMINGO 07 a 13
        $this->createHorarioDependencia(452, 1408, 'ld', '07:00', '13:00');
        //Efector MATERNIDAD Servicio UNIDAD NEO II
        // 07 a 13 PERSONALIZADO LUNES, MARTES, MIERCOLES, SABADO Y DOMINGO
        $dias = [
            ['id' => null, 'nombre' => 'Lunes', 'hora_desde' => '07:00', 'hora_hasta' => '13:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Martes', 'hora_desde' => '07:00', 'hora_hasta' => '13:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Miércoles', 'hora_desde' => '07:00', 'hora_hasta' => '13:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Jueves', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
            ['id' => null, 'nombre' => 'Viernes', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
            ['id' => null, 'nombre' => 'Sábado', 'hora_desde' => '07:00', 'hora_hasta' => '13:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Domingo', 'hora_desde' => '07:00', 'hora_hasta' => '13:00', 'isChecked' => true],
        ];
        $this->createHorarioDependencia(452, 1409, 'p', null, null, $dias);

        //Efector MATERNIDAD Servicio UNIDAD NEO III
        // 07 a 13 LUNES A VIERNES
        $this->createHorarioDependencia(452, 1410, 'lv', '07:00', '13:00');

        //Efector MATERNIDAD Servicio UNIDAD NEO IV
        // 0 a 23:59 LUNES A DOMINGO
        $this->createHorarioDependencia(452, 1411, 'ld', '00:00', '23:59');

        //Efector MATERNIDAD Servicio UNIDAD NEO V
        // 19 a 07 LUNES A VIERNES
        $this->createHorarioDependencia(452, 1412, 'lv', '19:00', '07:00');
        
        //Efector MATERNIDAD Servicio UNIDAD NEO VI
        // 08 a 12 LUNES A VIERNES
        $this->createHorarioDependencia(452, 1413, 'lv', '08:00', '12:00');

        //Efector MATERNIDAD Servicio UNIDAD NEO VII
        // 23:59 a 23:59 LUNES A VIERNES
        $this->createHorarioDependencia(452, 1414, 'lv', '23:59', '23:59');

        //Efector MATERNIDAD Servicio UNIDAD NEO VIII
        // 08 a 12 LUNES A DOMINGO
        $this->createHorarioDependencia(452, 1415, 'ld', '08:00', '12:00');

        //Efector MATERNIDAD Servicio UNIDAD NEO IX
        // 06 a 12 PERSONALIZADO LUNES, MARTES, MIERCOLES, SABADO Y DOMINGO
        $dias = [
            ['id' => null, 'nombre' => 'Lunes', 'hora_desde' => '06:00', 'hora_hasta' => '12:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Martes', 'hora_desde' => '06:00', 'hora_hasta' => '12:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Miércoles', 'hora_desde' => '06:00', 'hora_hasta' => '12:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Jueves', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
            ['id' => null, 'nombre' => 'Viernes', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
            ['id' => null, 'nombre' => 'Sábado', 'hora_desde' => '06:00', 'hora_hasta' => '12:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Domingo', 'hora_desde' => '06:00', 'hora_hasta' => '12:00', 'isChecked' => true],
        ];
        $this->createHorarioDependencia(452, 1416, 'p', null, null, $dias);

        //Efector MATERNIDAD Servicio UNIDAD NEO X
        // 06 a 12 PERSONALIZADO LUNES, MARTES, MIERCOLES, SABADO Y DOMINGO
        $dias = [
            ['id' => null, 'nombre' => 'Lunes', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Martes', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Miércoles', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Jueves', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
            ['id' => null, 'nombre' => 'Viernes', 'hora_desde' => null, 'hora_hasta' => null, 'isChecked' => false],
            ['id' => null, 'nombre' => 'Sábado', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Domingo', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => true],
        ];
        $this->createHorarioDependencia(452, 1417, 'p', null, null, $dias);

        //Efector MATERNIDAD Servicio Departamento personal
        // 19:00 a 02:00 LUNES A VIERNES
        $this->createHorarioDependencia(452, 1689, 'lv', '19:00', '02:00');

        //Efector MATERNIDAD Servicio UNIDAD NEO X
        // 06 a 14 PERSONALIZADO LUNES, MARTES, MIERCOLES, JUEVES Y VIERNES
        $dias = [
            ['id' => null, 'nombre' => 'Lunes', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Martes', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Miércoles', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Jueves', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Viernes', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Sábado', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => false],
            ['id' => null, 'nombre' => 'Domingo', 'hora_desde' => '06:00', 'hora_hasta' => '14:00', 'isChecked' => false],
        ];
        $this->createHorarioDependencia(452, 1688, 'p', null, null, $dias);

        //Efector Hospital Regional Concepción
        // 24/7 LUNES A DOMINGO
        $this->createHorarioDependencia(440, 440, 'ld', '00:00', '00:00');
        //Efector Hospital Regional Concepción Servicio Ginecología
        $dias = [
            ['id' => null, 'nombre' => 'Lunes', 'hora_desde' => '07:00', 'hora_hasta' => '19:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Martes', 'hora_desde' => '07:00', 'hora_hasta' => '19:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Miércoles', 'hora_desde' => '07:00', 'hora_hasta' => '19:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Jueves', 'hora_desde' => '07:00', 'hora_hasta' => '19:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Viernes', 'hora_desde' => '07:00', 'hora_hasta' => '19:00', 'isChecked' => true],
            ['id' => null, 'nombre' => 'Sábado', 'hora_desde' => '07:30', 'hora_hasta' => '13:30', 'isChecked' => false],
            ['id' => null, 'nombre' => 'Domingo', 'hora_desde' => '07:30', 'hora_hasta' => '13:30', 'isChecked' => false],
        ];
        $this->createHorarioDependencia(440, 1355, 'p', null, null, $dias);
    }

    // /* PERSONALIZADO */

    // //Cargo Personalizado -> 2 Servicios personalizado y rotativo
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_y_rotativo_en_servicio_con_horario_personalizado_fuera_de_rango_superior()
    // {
    //     $this->addEfector();
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][1]['idefector'] = 452;
    //     $this->payload['efectores'][1]['idservicio'] = 1654;
    //     $this->payload['efectores'][1]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][1]['hora_desde'] = '14:00';
    //     $this->payload['efectores'][1]['hora_hasta'] = '20:00';
    //     $this->payload['efectores'][1]['cantidad_mensual'] = '8';

    //     //Segundo Efector
    //     $this->payload['efectores'][0]['idefector'] = 440;
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'p';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',  'hora_desde' => '14:00',    'hora_hasta' => '20:00',  'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes', 'hora_desde' => '14:00',    'hora_hasta' => '20:00',  'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',  'hora_desde' => '14:00',    'hora_hasta' => '20:00',  'isChecked' => true];

    //     //Usuario RRHH
    //     $estela = User::find(21);

    //     $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Personalizado -> 2 Servicios personalizados
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_en_servicio_con_horario_personalizado_fuera_de_rango_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idefector'] = 440;
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'p';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',  'hora_desde' => '14:00',    'hora_hasta' => '20:00',  'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes', 'hora_desde' => '14:00',    'hora_hasta' => '20:00',  'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',  'hora_desde' => '14:00',    'hora_hasta' => '20:00',  'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves', 'hora_desde' => '14:00',    'hora_hasta' => '20:00',  'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',  'hora_desde' => '14:00',    'hora_hasta' => '20:00',  'isChecked' => true];

    //     //Usuario RRHH
    //     $estela = User::find(21);

    //     $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Personalizado -> 2 Servicios personalizados
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_en_dos_servicios_con_horario_lad_y_personalizado_fuera_de_rango_inferior_y_superior()
    // {
    //     $this->addEfector();
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'p';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',  'hora_desde' => '06:00',    'hora_hasta' => '14:00',  'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes', 'hora_desde' => '06:00',    'hora_hasta' => '14:00',  'isChecked' => true];

    //     //Segundo Efector
    //     $this->payload['efectores'][1]['idservicio'] = 1409;
    //     $this->payload['efectores'][1]['tipoHorario'] = 'p';
    //     $this->payload['efectores'][1]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '06:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][1]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];

    //     //Usuario RRHH
    //     $estela = User::find(21);

    //     $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }
    
    // //Cargo Personalizado -> 2 Servicios LaD
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_en_dos_servicios_con_horario_lad_y_personalizado_fuera_de_rango_inferior()
    // {
    //     $this->addEfector();
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'p';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];

    //     //Segundo Efector
    //     $this->payload['efectores'][1]['idservicio'] = 1409;
    //     $this->payload['efectores'][1]['tipoHorario'] = 'p';
    //     $this->payload['efectores'][1]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
    //     $this->payload['efectores'][1]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
    //     $this->payload['efectores'][1]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];

    //     //Usuario RRHH
    //     $estela = User::find(21);

    //     $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }
    
    // //Cargo Personalizado -> 1 Servicios personalizado 1 Servicio LaD
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_en_dos_servicios_con_horario_lad_y_personalizado_fuera_de_rango_superior()
    // {
    //     $this->addEfector();
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'p';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];

    //     //Segundo Efector
    //     $this->payload['efectores'][1]['idservicio'] = 1409;
    //     $this->payload['efectores'][1]['tipoHorario'] = 'p';
    //     $this->payload['efectores'][1]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][1]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][1]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];

    //     //Usuario RRHH
    //     $estela = User::find(21);

    //     $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Personalizado -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_en_servicio_con_horario_lav_fuera_rango_inferior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'p';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '05:00',    'hora_hasta' => '11:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miercoles', 'hora_desde' => '04:00',    'hora_hasta' => '10:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '03:00',    'hora_hasta' => '09:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '02:00',    'hora_hasta' => '08:00',    'isChecked' => true];

    //     //Usuario RRHH
    //     $estela = User::find(21);

    //     $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Personalizado -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_en_servicio_con_horario_lav_fuera_rango_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'p';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miercoles', 'hora_desde' => '09:00',    'hora_hasta' => '15:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '10:00',    'hora_hasta' => '16:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '11:00',    'hora_hasta' => '17:00',    'isChecked' => true];

    //     //Usuario RRHH
    //     $estela = User::find(21);

    //     $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Personalizado -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_en_servicio_con_horario_lav_fuera_rango_inferior_y_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'p';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miercoles', 'hora_desde' => '09:00',    'hora_hasta' => '15:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '10:00',    'hora_hasta' => '16:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '11:00',    'hora_hasta' => '17:00',    'isChecked' => true];
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // /* PERSONALIZADO GUARDIA */
    // //Cargo Personalizado Guardia -> Servicio LaD y Servicio Personalizado
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_guardia_en_dos_servicios_con_horario_lad_y_personalizado_fuera_de_rango_inferior_y_superior()
    // {
    //     $this->addEfector();
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'pg';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',  'hora_desde' => '06:00',    'hora_hasta' => '14:00',  'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes', 'hora_desde' => '06:00',    'hora_hasta' => '14:00',  'isChecked' => true];

    //     //Segundo Efector
    //     $this->payload['efectores'][1]['idservicio'] = 1409;
    //     $this->payload['efectores'][1]['tipoHorario'] = 'pg';
    //     $this->payload['efectores'][1]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '06:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][1]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];

    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }
    
    // //Cargo Personalizado Guardia -> Servicio LaD y Servicio Personalizado
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_guardia_en_dos_servicios_con_horario_lad_y_personalizado_fuera_de_rango_inferior()
    // {
    //     $this->addEfector();
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'pg';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];

    //     //Segundo Efector
    //     $this->payload['efectores'][1]['idservicio'] = 1409;
    //     $this->payload['efectores'][1]['tipoHorario'] = 'pg';
    //     $this->payload['efectores'][1]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
    //     $this->payload['efectores'][1]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
    //     $this->payload['efectores'][1]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];

    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }
    
    // //Cargo Personalizado Guardia -> Servicio LaD y Servicio Personalizado
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_guardia_en_dos_servicios_con_horario_lad_y_personalizado_fuera_de_rango_superior()
    // {
    //     $this->addEfector();
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'pg';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];

    //     //Segundo Efector
    //     $this->payload['efectores'][1]['idservicio'] = 1409;
    //     $this->payload['efectores'][1]['tipoHorario'] = 'pg';
    //     $this->payload['efectores'][1]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][1]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][1]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];

    //     //Usuario RRHH
    //     $estela = User::find(21);

    //     $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Personalizado Guardia -> Servicio LaD
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_guardia_en_servicio_con_horario_lad_fuera_rango_inferior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'pg';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '05:00',    'hora_hasta' => '11:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miercoles', 'hora_desde' => '04:00',    'hora_hasta' => '10:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '03:00',    'hora_hasta' => '09:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '02:00',    'hora_hasta' => '08:00',    'isChecked' => true];

    //     //Usuario RRHH
    //     $estela = User::find(21);

    //     $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Personalizado Guardia -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_guardia_en_servicio_con_horario_lav_fuera_rango_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'pg';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miercoles', 'hora_desde' => '09:00',    'hora_hasta' => '15:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '10:00',    'hora_hasta' => '16:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '11:00',    'hora_hasta' => '17:00',    'isChecked' => true];

    //     //Usuario RRHH
    //     $estela = User::find(21);

    //     $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Personalizado Guardia -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_personalizado_guardia_en_dos_servicios_con_horario_lav_fuera_rango_inferior_y_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'pg';
    //     $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miercoles', 'hora_desde' => '09:00',    'hora_hasta' => '15:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '10:00',    'hora_hasta' => '16:00',    'isChecked' => true];
    //     $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '11:00',    'hora_hasta' => '17:00',    'isChecked' => true];
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }
    
    // /* LUNES A VIERNES */
    // //Cargo LaV -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_lav_en_servicio_con_horario_lav_fuera_rango_inferior_y_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1413;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '07:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '13:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo LaV -> Servicio LaD
    // /** @test */
    // public function no_crea_cargo_horario_lav_en_servicio_con_horario_lad_fuera_rango_inferior_y_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1415;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '07:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '13:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo LaV -> Servicio Personalziado
    // /** @test */
    // public function no_crea_cargo_horario_lav_en_servicio_con_horario_personalizado_fuera_rango_inferior_y_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1416;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '07:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '13:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo LaV -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_lav_en_servicio_con_horario_lav_fuera_rango_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '08:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '14:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo LaV -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_lav_en_servicio_con_horario_lav_fuera_rango_inferior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '06:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '12:00';
         
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo LaV -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_lav_en_servicio_con_horario_lav_fuera_rango_inferior_2()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '06:59';
    //     $this->payload['efectores'][0]['hora_hasta'] = '12:59';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo LaV -> Servicio LaD
    // /** @test */
    // public function no_crea_cargo_horario_lav_en_servicio_con_horario_lad_fuera_rango_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '08:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '14:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo LaV -> Servicio LaD
    // /** @test */
    // public function no_crea_cargo_horario_lav_en_servicio_con_horario_lad_fuera_rango_inferior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '06:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '12:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo LaV -> Servicio LaD
    // /** @test */
    // public function no_crea_cargo_horario_lav_en_servicio_con_horario_lad_fuera_rango_inferior_2()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '06:59';
    //     $this->payload['efectores'][0]['hora_hasta'] = '12:59';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo LaV -> Servicio Personalizado
    // /** @test */
    // public function no_crea_cargo_horario_lav_en_servicio_con_horario_personalizado_fuera_rango_inferior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1409;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '06:59';
    //     $this->payload['efectores'][0]['hora_hasta'] = '12:59';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo LaV -> Servicio Personalizado
    // /** @test */
    // public function no_crea_cargo_horario_lav_en_servicio_con_horario_personalizado_fuera_rango_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1409;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'lv';
    //     $this->payload['efectores'][0]['hora_desde'] = '08:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '14:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    
    // /* ROTATIVO */
    // //Cargo Rotativo -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_rotativo_en_servicio_con_horario_lav_fuera_rango_inferior_y_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1413;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][0]['cantidad_mensual'] = 20;
    //     $this->payload['efectores'][0]['hora_desde'] = '07:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '13:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Rotativo -> Servicio LaD
    // /** @test */
    // public function no_crea_cargo_horario_rotativo_en_servicio_con_horario_lad_fuera_rango_inferior_y_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1415;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][0]['cantidad_mensual'] = 20;
    //     $this->payload['efectores'][0]['hora_desde'] = '07:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '13:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Rotativo -> Servicio Personalziado
    // /** @test */
    // public function no_crea_cargo_horario_rotativo_en_servicio_con_horario_personalizado_fuera_rango_inferior_y_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1416;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][0]['cantidad_mensual'] = 20;
    //     $this->payload['efectores'][0]['hora_desde'] = '07:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '13:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Rotativo -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_rotativo_en_servicio_con_horario_lav_fuera_rango_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][0]['cantidad_mensual'] = 20;
    //     $this->payload['efectores'][0]['hora_desde'] = '08:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '14:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Rotativo -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_rotativo_en_servicio_con_horario_lav_fuera_rango_inferior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][0]['cantidad_mensual'] = 20;
    //     $this->payload['efectores'][0]['hora_desde'] = '06:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '12:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Rotativo -> Servicio LaV
    // /** @test */
    // public function no_crea_cargo_horario_rotativo_en_servicio_con_horario_lav_fuera_rango_inferior_2()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1410;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][0]['cantidad_mensual'] = 20;
    //     $this->payload['efectores'][0]['hora_desde'] = '06:59';
    //     $this->payload['efectores'][0]['hora_hasta'] = '12:59';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Rotativo -> Servicio LaD
    // /** @test */
    // public function no_crea_cargo_horario_rotativo_en_servicio_con_horario_lad_fuera_rango_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][0]['cantidad_mensual'] = 20;
    //     $this->payload['efectores'][0]['hora_desde'] = '08:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '14:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Rotativo -> Servicio LaD
    // /** @test */
    // public function no_crea_cargo_horario_rotativo_en_servicio_con_horario_lad_fuera_rango_inferior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][0]['cantidad_mensual'] = 20;
    //     $this->payload['efectores'][0]['hora_desde'] = '06:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '12:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Rotativo -> Servicio LaD
    // /** @test */
    // public function no_crea_cargo_horario_rotativo_en_servicio_con_horario_lad_fuera_rango_inferior_2()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1408;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][0]['cantidad_mensual'] = 20;
    //     $this->payload['efectores'][0]['hora_desde'] = '06:59';
    //     $this->payload['efectores'][0]['hora_hasta'] = '12:59';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Rotativo -> Servicio Personalizado
    // /** @test */
    // public function no_crea_cargo_horario_rotativo_en_servicio_con_horario_personalizado_fuera_rango_inferior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1409;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][0]['cantidad_mensual'] = 20;
    //     $this->payload['efectores'][0]['hora_desde'] = '06:59';
    //     $this->payload['efectores'][0]['hora_hasta'] = '12:59';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    // //Cargo Rotativo -> Servicio Personalizado
    // /** @test */
    // public function no_crea_cargo_horario_rotativo_en_servicio_con_horario_personalizado_fuera_rango_superior()
    // {
    //     $this->resetDias();
    //     //Primer Efector
    //     $this->payload['efectores'][0]['idservicio'] = 1409;
    //     $this->payload['efectores'][0]['tipoHorario'] = 'rot';
    //     $this->payload['efectores'][0]['cantidad_mensual'] = 20;
    //     $this->payload['efectores'][0]['hora_desde'] = '08:00';
    //     $this->payload['efectores'][0]['hora_hasta'] = '14:00';
        
    //     $response = $this->json('POST', '/api/cargo', $this->payload);

    //     $response->assertStatus(201);
    //     $response->assertJson([
    //         'success' => false,
    //         'message' => 'El Agente ingresó horarios fuera del rango horario del Servicio o Efector.'
    //     ]);
    // }

    //Cargo Personalizado -> 1 Servicio LaD, 1 Servicio LaV y 1 Servicio Personalziado
    /** @test */
    public function crea_cargo_horario_personalizado_en_tres_servicios_con_horario_lad_lav_y_personalizado_igual_a_rango()
    {
        $this->addEfector();
        $this->addEfector();
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1408;
        $this->payload['efectores'][0]['tipoHorario'] = 'p';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];

        //Segundo Efector
        $this->payload['efectores'][1]['idservicio'] = 1409;
        $this->payload['efectores'][1]['tipoHorario'] = 'p';
        $this->payload['efectores'][1]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][1]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];

        //Tercer Efector
        $this->payload['efectores'][2]['idservicio'] = 1410;
        $this->payload['efectores'][2]['tipoHorario'] = 'p';
        $this->payload['efectores'][2]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '07:00',    'hora_hasta' => '13:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado -> Servicio Personalziado
    /** @test */
    public function crea_cargo_horario_personalizado_en_servicio_con_horario_personalizado_dentro_de_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1417;
        $this->payload['efectores'][0]['tipoHorario'] = 'p';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '07:00',    'hora_hasta' => '13:00',  'isChecked' => true];
       
        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado -> Servicio Personalziado
    /** @test */
    public function crea_cargo_horario_personalizado_en_servicio_con_horario_personalizado_extremo_inferior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1417;
        $this->payload['efectores'][0]['tipoHorario'] = 'p';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '06:00',    'hora_hasta' => '12:00',  'isChecked' => true];
        
        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado -> Servicio Personalziado
    /** @test */
    public function crea_cargo_horario_personalizado_en_servicio_con_horario_personalizado_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1417;
        $this->payload['efectores'][0]['tipoHorario'] = 'p';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '08:00',    'hora_hasta' => '14:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_personalizado_en_servicio_con_horario_lad_dentro_de_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1411;
        $this->payload['efectores'][0]['tipoHorario'] = 'p';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '07:00',    'hora_hasta' => '13:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_personalizado_en_servicio_con_horario_lad_extremo_inferior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1411;
        $this->payload['efectores'][0]['tipoHorario'] = 'p';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '00:00',    'hora_hasta' => '06:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '00:00',    'hora_hasta' => '06:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '00:00',    'hora_hasta' => '06:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '00:00',    'hora_hasta' => '06:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '00:00',    'hora_hasta' => '06:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_personalizado_en_servicio_con_horario_lad_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1411;
        $this->payload['efectores'][0]['tipoHorario'] = 'p';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '17:59',    'hora_hasta' => '23:59',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '17:59',    'hora_hasta' => '23:59',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '17:59',    'hora_hasta' => '23:59',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '17:59',    'hora_hasta' => '23:59',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '17:59',    'hora_hasta' => '23:59',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_personalizado_en_servicio_con_horario_lav_dentro_de_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1412;
        $this->payload['efectores'][0]['tipoHorario'] = 'p';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '20:00',    'hora_hasta' => '02:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '20:00',    'hora_hasta' => '02:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '20:00',    'hora_hasta' => '02:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '20:00',    'hora_hasta' => '02:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '20:00',    'hora_hasta' => '02:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_personalizado_en_servicio_con_horario_lav_extremo_inferior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1412;
        $this->payload['efectores'][0]['tipoHorario'] = 'p';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '19:00',    'hora_hasta' => '01:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '19:00',    'hora_hasta' => '01:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '19:00',    'hora_hasta' => '01:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '20:00',    'hora_hasta' => '02:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '20:00',    'hora_hasta' => '02:00',  'isChecked' => true];
        
        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_personalizado_en_servicio_con_horario_lav_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1412;
        $this->payload['efectores'][0]['tipoHorario'] = 'p';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '01:00',    'hora_hasta' => '07:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '01:00',    'hora_hasta' => '07:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '01:00',    'hora_hasta' => '07:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '01:00',    'hora_hasta' => '07:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '01:00',    'hora_hasta' => '07:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_personalizado_en_servicio_con_horario_lav_con_horario_de_dos_dias()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1689;
        $this->payload['efectores'][0]['tipoHorario'] = 'p';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '19:00',    'hora_hasta' => '01:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '20:00',    'hora_hasta' => '02:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '19:20',    'hora_hasta' => '01:20',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '19:00',    'hora_hasta' => '01:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '19:00',    'hora_hasta' => '01:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    
    //Cargo Personalizado Guardia -> 1 Servicio LaD, 1 Servicio LaV y 1 Servicio Personalziado
    /** @test */
    public function crea_cargo_horario_personalizado_guardia_en_tres_servicios_con_horario_lad_lav_y_personalizado_igual_a_rango()
    {
        $this->addEfector();
        $this->addEfector();
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1408;
        $this->payload['efectores'][0]['tipoHorario'] = 'pg';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];

        //Segundo Efector
        $this->payload['efectores'][1]['idservicio'] = 1409;
        $this->payload['efectores'][1]['tipoHorario'] = 'pg';
        $this->payload['efectores'][1]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][1]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];

        //Tercer Efector
        $this->payload['efectores'][2]['idservicio'] = 1410;
        $this->payload['efectores'][2]['tipoHorario'] = 'pg';
        $this->payload['efectores'][2]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '07:00',    'hora_hasta' => '13:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado Guardia -> Servicio Personalziado
    /** @test */
    public function crea_cargo_horario_personalizado_guardia_en_servicio_con_horario_personalizado_dentro_de_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1417;
        $this->payload['efectores'][0]['tipoHorario'] = 'pg';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '07:00',    'hora_hasta' => '13:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado Guardia -> Servicio Personalziado
    /** @test */
    public function crea_cargo_horario_personalizado_guardia_en_servicio_con_horario_personalizado_extremo_inferior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1417;
        $this->payload['efectores'][0]['tipoHorario'] = 'pg';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '06:00',    'hora_hasta' => '12:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '06:00',    'hora_hasta' => '12:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado Guardia -> Servicio Personalziado
    /** @test */
    public function crea_cargo_horario_personalizado_guardia_en_servicio_con_horario_personalizado_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1417;
        $this->payload['efectores'][0]['tipoHorario'] = 'pg';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '08:00',    'hora_hasta' => '14:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '08:00',    'hora_hasta' => '14:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado Guardia -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_personalizado_guardia_en_servicio_con_horario_lad_dentro_de_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1411;
        $this->payload['efectores'][0]['tipoHorario'] = 'pg';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '07:00',    'hora_hasta' => '13:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '07:00',    'hora_hasta' => '13:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado Guardia -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_personalizado_guardia_en_servicio_con_horario_lad_extremo_inferior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1411;
        $this->payload['efectores'][0]['tipoHorario'] = 'pg';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '00:00',    'hora_hasta' => '06:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '00:00',    'hora_hasta' => '06:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '00:00',    'hora_hasta' => '06:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '00:00',    'hora_hasta' => '06:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '00:00',    'hora_hasta' => '06:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado Guardia -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_personalizado_guardia_en_servicio_con_horario_lad_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1411;
        $this->payload['efectores'][0]['tipoHorario'] = 'pg';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '17:59',    'hora_hasta' => '23:59',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '17:59',    'hora_hasta' => '23:59',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '17:59',    'hora_hasta' => '23:59',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][5] = ['id' => null, 'nombre' => 'Sábado',    'hora_desde' => '17:59',    'hora_hasta' => '23:59',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][6] = ['id' => null, 'nombre' => 'Domingo',   'hora_desde' => '17:59',    'hora_hasta' => '23:59',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado Guardia -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_personalizado_guardia_en_servicio_con_horario_lav_dentro_de_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1412;
        $this->payload['efectores'][0]['tipoHorario'] = 'pg';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '20:00',    'hora_hasta' => '02:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '20:00',    'hora_hasta' => '02:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '20:00',    'hora_hasta' => '02:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '20:00',    'hora_hasta' => '02:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '20:00',    'hora_hasta' => '02:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado Guardia -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_personalizado_guardia_en_servicio_con_horario_lav_extremo_inferior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1412;
        $this->payload['efectores'][0]['tipoHorario'] = 'pg';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '19:00',    'hora_hasta' => '01:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '19:00',    'hora_hasta' => '01:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '19:00',    'hora_hasta' => '01:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '19:00',    'hora_hasta' => '01:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '19:00',    'hora_hasta' => '01:00',  'isChecked' => true];

        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Personalizado Guardia -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_personalizado_guardia_en_servicio_con_horario_lav_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1412;
        $this->payload['efectores'][0]['tipoHorario'] = 'pg';
        $this->payload['efectores'][0]['dias'][0] = ['id' => null, 'nombre' => 'Lunes',     'hora_desde' => '01:00',    'hora_hasta' => '07:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][1] = ['id' => null, 'nombre' => 'Martes',    'hora_desde' => '01:00',    'hora_hasta' => '07:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][2] = ['id' => null, 'nombre' => 'Miércoles',    'hora_desde' => '01:00',    'hora_hasta' => '07:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][3] = ['id' => null, 'nombre' => 'Jueves',    'hora_desde' => '01:00',    'hora_hasta' => '07:00',    'isChecked' => true];
        $this->payload['efectores'][0]['dias'][4] = ['id' => null, 'nombre' => 'Viernes',   'hora_desde' => '01:00',    'hora_hasta' => '07:00',  'isChecked' => true];
        
        //Usuario RRHH
        $estela = User::find(21);

        $response = $this->actingAs($estela)->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    /* LUNES A VIERNES */
    //Cargo LaV -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_lav_en_servicio_con_horario_lav_rango_iguales_a_extremos()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1410;
        $this->payload['efectores'][0]['tipoHorario'] = 'lv';
        $this->payload['efectores'][0]['hora_desde'] = '07:00';
        $this->payload['efectores'][0]['hora_hasta'] = '13:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo LaV -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_lav_en_servicio_con_horario_lad_extremo_inferior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1411;
        $this->payload['efectores'][0]['tipoHorario'] = 'lv';
        $this->payload['efectores'][0]['hora_desde'] = '00:00';
        $this->payload['efectores'][0]['hora_hasta'] = '06:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo LaV -> Servicio Personalziado
    /** @test */
    public function crea_cargo_horario_lav_en_servicio_con_horario_personalizado_extremo_inferior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1688;
        $this->payload['efectores'][0]['tipoHorario'] = 'lv';
        $this->payload['efectores'][0]['hora_desde'] = '06:00';
        $this->payload['efectores'][0]['hora_hasta'] = '12:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo LaV -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_lav_en_servicio_con_horario_lav_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1688;
        $this->payload['efectores'][0]['tipoHorario'] = 'lv';
        $this->payload['efectores'][0]['hora_desde'] = '08:00';
        $this->payload['efectores'][0]['hora_hasta'] = '14:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo LaV -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_lav_en_servicio_con_horario_lav_extremos_dentro_de_extremos()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1412;
        $this->payload['efectores'][0]['tipoHorario'] = 'lv';
        $this->payload['efectores'][0]['hora_desde'] = '20:00';
        $this->payload['efectores'][0]['hora_hasta'] = '02:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo LaV -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_lav_en_servicio_con_horario_lad_extremos_iguales()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1408;
        $this->payload['efectores'][0]['tipoHorario'] = 'lv';
        $this->payload['efectores'][0]['hora_desde'] = '07:00';
        $this->payload['efectores'][0]['hora_hasta'] = '13:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo LaV -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_lav_en_servicio_con_horario_lad_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1411;
        $this->payload['efectores'][0]['tipoHorario'] = 'lv';
        $this->payload['efectores'][0]['hora_desde'] = '17:59';
        $this->payload['efectores'][0]['hora_hasta'] = '23:59';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo LaV -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_lav_en_servicio_con_horario_lad_extremo_superior_igual_a_rango_2()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1411;
        $this->payload['efectores'][0]['tipoHorario'] = 'lv';
        $this->payload['efectores'][0]['hora_desde'] = '18:00';
        $this->payload['efectores'][0]['hora_hasta'] = '00:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo LaV -> Servicio Personalizado
    /** @test */
    public function crea_cargo_horario_lav_en_servicio_con_horario_personalizado_extremos_iguales()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1688;
        $this->payload['efectores'][0]['tipoHorario'] = 'lv';
        $this->payload['efectores'][0]['hora_desde'] = '07:00';
        $this->payload['efectores'][0]['hora_hasta'] = '13:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo LaV -> Servicio Personalizado
    /** @test */
    public function crea_cargo_horario_lav_en_servicio_con_horario_personalizado_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1688;
        $this->payload['efectores'][0]['tipoHorario'] = 'lv';
        $this->payload['efectores'][0]['hora_desde'] = '08:00';
        $this->payload['efectores'][0]['hora_hasta'] = '14:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }
    
    /* ROTATIVO */
    //Cargo Rotativo -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_rotativo_en_servicio_con_horario_lav_extremos_iguales()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1410;
        $this->payload['efectores'][0]['tipoHorario'] = 'rot';
        $this->payload['efectores'][0]['cantidad_mensual'] = 20;
        $this->payload['efectores'][0]['hora_desde'] = '07:00';
        $this->payload['efectores'][0]['hora_hasta'] = '13:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Rotativo -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_rotativo_en_servicio_con_horario_lad_extremos_iguales()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1410;
        $this->payload['efectores'][0]['tipoHorario'] = 'rot';
        $this->payload['efectores'][0]['cantidad_mensual'] = 20;
        $this->payload['efectores'][0]['hora_desde'] = '07:00';
        $this->payload['efectores'][0]['hora_hasta'] = '13:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Rotativo -> Servicio Personalziado
    /** @test */
    public function crea_cargo_horario_rotativo_en_servicio_con_horario_personalizado_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1417;
        $this->payload['efectores'][0]['tipoHorario'] = 'rot';
        $this->payload['efectores'][0]['cantidad_mensual'] = 20;
        $this->payload['efectores'][0]['hora_desde'] = '08:00';
        $this->payload['efectores'][0]['hora_hasta'] = '14:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Rotativo -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_rotativo_en_servicio_con_horario_lav_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1412;
        $this->payload['efectores'][0]['tipoHorario'] = 'rot';
        $this->payload['efectores'][0]['cantidad_mensual'] = 20;
        $this->payload['efectores'][0]['hora_desde'] = '01:00';
        $this->payload['efectores'][0]['hora_hasta'] = '07:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Rotativo -> Servicio LaV
    /** @test */
    public function crea_cargo_horario_rotativo_en_servicio_con_horario_lav_extremo_inferior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1412;
        $this->payload['efectores'][0]['tipoHorario'] = 'rot';
        $this->payload['efectores'][0]['cantidad_mensual'] = 20;
        $this->payload['efectores'][0]['hora_desde'] = '19:00';
        $this->payload['efectores'][0]['hora_hasta'] = '01:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Rotativo -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_rotativo_en_servicio_con_horario_lad_extremo_superior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1411;
        $this->payload['efectores'][0]['tipoHorario'] = 'rot';
        $this->payload['efectores'][0]['cantidad_mensual'] = 20;
        $this->payload['efectores'][0]['hora_desde'] = '17:59';
        $this->payload['efectores'][0]['hora_hasta'] = '23:59';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    //Cargo Rotativo -> Servicio LaD
    /** @test */
    public function crea_cargo_horario_rotativo_en_servicio_con_horario_lad_extremo_inferior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1411;
        $this->payload['efectores'][0]['tipoHorario'] = 'rot';
        $this->payload['efectores'][0]['cantidad_mensual'] = 20;
        $this->payload['efectores'][0]['hora_desde'] = '00:00';
        $this->payload['efectores'][0]['hora_hasta'] = '06:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }


    //Cargo Rotativo -> Servicio Personalizado
    /** @test */
    public function crea_cargo_horario_rotativo_en_servicio_con_horario_personalizado_extremo_inferior_igual_a_rango()
    {
        $this->resetDias();
        //Primer Efector
        $this->payload['efectores'][0]['idservicio'] = 1417;
        $this->payload['efectores'][0]['tipoHorario'] = 'rot';
        $this->payload['efectores'][0]['cantidad_mensual'] = 20;
        $this->payload['efectores'][0]['hora_desde'] = '06:00';
        $this->payload['efectores'][0]['hora_hasta'] = '12:00';
        
        $response = $this->json('POST', '/api/cargo', $this->payload);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'El cargo fue creado correctamente.'
        ]);
    }

    private function defaultValuesCargo()
    {
        return [
            'id' => null,
            'idtipo_cese' => 22,
            'agente_reemplazado' => false,
            'cargo_vacante' => false,
            'dniReemplazado' => null,
            'dniPropuesto' => "33762078",
            'idtipo_agrupamiento' => 1,
            'idtipo_campania' => 1,
            'idtipo_especialidad' => 63,
            'tipoAgrupamiento' => null,
            'idcargo_baja' => null,
            'produccion_esperada' => 'qwerqwe',
            'razones_brecha' => 'qwerqwer',
            'horasMensuales' => 120,
            'documentacion' => [
                'fotoCarnet' => false,
                'diagramaServicio' => false,
                'resolucionMinisterial' => false,
                'formularioBajaCobertura' => false,
                'tituloAcademico' => false,
                'copiaDni' => false,
                'copiaCuil' => false,
                'resumenEvaluacion' => false,
                'cursoInduccion' => false,
                'tituloEspecialidad' => false,
                'declaracionJurada' => false,
                'matriculaProfesional' => false,
                'certificadoReincidencia' => false,
            ],
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

    private function createHorarioDependencia($idefector, $idservicio, $tipoHorario, $hora_desde, $hora_hasta, $dias = null): void
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
