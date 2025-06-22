<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TipoEntrevista;

class TipoEntrevistaApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_tipo_entrevista()
    {
        $tipoEntrevista = factory(TipoEntrevista::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/tipo_entrevistas', $tipoEntrevista
        );

        $this->assertApiResponse($tipoEntrevista);
    }

    /**
     * @test
     */
    public function test_read_tipo_entrevista()
    {
        $tipoEntrevista = factory(TipoEntrevista::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/tipo_entrevistas/'.$tipoEntrevista->id
        );

        $this->assertApiResponse($tipoEntrevista->toArray());
    }

    /**
     * @test
     */
    public function test_update_tipo_entrevista()
    {
        $tipoEntrevista = factory(TipoEntrevista::class)->create();
        $editedTipoEntrevista = factory(TipoEntrevista::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/tipo_entrevistas/'.$tipoEntrevista->id,
            $editedTipoEntrevista
        );

        $this->assertApiResponse($editedTipoEntrevista);
    }

    /**
     * @test
     */
    public function test_delete_tipo_entrevista()
    {
        $tipoEntrevista = factory(TipoEntrevista::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/tipo_entrevistas/'.$tipoEntrevista->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/tipo_entrevistas/'.$tipoEntrevista->id
        );

        $this->response->assertStatus(404);
    }
}
