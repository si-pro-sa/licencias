<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\CapacitacionAgente;

class CapacitacionAgenteApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_capacitacion_agente()
    {
        $capacitacionAgente = factory(CapacitacionAgente::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/capacitacion_agentes', $capacitacionAgente
        );

        $this->assertApiResponse($capacitacionAgente);
    }

    /**
     * @test
     */
    public function test_read_capacitacion_agente()
    {
        $capacitacionAgente = factory(CapacitacionAgente::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/capacitacion_agentes/'.$capacitacionAgente->id
        );

        $this->assertApiResponse($capacitacionAgente->toArray());
    }

    /**
     * @test
     */
    public function test_update_capacitacion_agente()
    {
        $capacitacionAgente = factory(CapacitacionAgente::class)->create();
        $editedCapacitacionAgente = factory(CapacitacionAgente::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/capacitacion_agentes/'.$capacitacionAgente->id,
            $editedCapacitacionAgente
        );

        $this->assertApiResponse($editedCapacitacionAgente);
    }

    /**
     * @test
     */
    public function test_delete_capacitacion_agente()
    {
        $capacitacionAgente = factory(CapacitacionAgente::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/capacitacion_agentes/'.$capacitacionAgente->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/capacitacion_agentes/'.$capacitacionAgente->id
        );

        $this->response->assertStatus(404);
    }
}
