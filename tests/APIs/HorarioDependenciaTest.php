<?php

namespace Tests\APIs;

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\ApiTestTrait;
use Tests\TestCase;

class HorarioDependenciaTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware;

    protected $payload = null;
    protected $created = false;

    public function setUp(): void
    {
        parent::setUp();

        if (!$this->created) {
            $this->createAllDependencias();
            $this->created = true;
        }
    }

    /**
     * Reset the migrations
     */
    public function tearDown() : void
    {
        $this->artisan('migrate:reset');
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

    private function createHorarioDependencia($idefector, $idservicio, $tipoHorario, $hora_desde, $hora_hasta, $dias = null)
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
        return $this->json('POST', '/api/horario-dependencia', $payload);
    }
}
