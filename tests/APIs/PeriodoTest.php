<?php

namespace Tests\APIs;

use Tests\TestCase;
use App\Models\Periodo;

class PeriodoTest extends TestCase
{
    /** @test */
    public function obtener_ids_de_periodos_con_rango_de_fechas()
    {
        $periodos = Periodo::getIdsPeriodos('2020-10-01', '2020-11-01');
        $this->assertCount(2, $periodos['ids']);

        $periodos = Periodo::getIdsPeriodos('2020-10-01', '2021-03-01');
        $this->assertCount(6, $periodos['ids']);

        $periodos = Periodo::getIdsPeriodos('2020-02-28', '2020-02-29');
        $this->assertCount(1, $periodos['ids']);

        $periodos = Periodo::getIdsPeriodos('2020-01-31', '2020-02-29');
        $this->assertCount(2, $periodos['ids']);

        $periodos = Periodo::getIdsPeriodos('2020-01-31', '2020-03-29');
        $this->assertCount(3, $periodos['ids']);

        $periodos = Periodo::getIdsPeriodos('2020-10-31', '2020-03-29');
        $this->assertCount(1, $periodos['ids']);
    }
}
