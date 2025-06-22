<?php
namespace Tests\APIs;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\ApiTestTrait;

class ContadorHorasTest extends TestCase
{
    use ApiTestTrait;
    use WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $lmema = User::find(19);

        $tipoFormulario = 'LIBRE_DISPONIBILIDAD';
        $horasNuevas = 260;
        $fechaDesde = '2021-05-01';
        $fechaHasta = '2021-09-30';
        $idagente = 30620;
        $idtipo_campania = 1;
        $idtipo_guardia = 1;
        $response = $this->actingAs($lmema)->json('GET', "/api/supera-limite-horas-mensuales/{$idagente}/{$tipoFormulario}/{$horasNuevas}/{$fechaDesde}/{$fechaHasta}/{$idtipo_campania}/{$idtipo_guardia}");

        $response->assertJson([
            'success' => false,
            'data' => true,
            'message' => 'El Agente está dentro de los límites horarios.'
        ]);
        $response->assertStatus(201);
    }
}
