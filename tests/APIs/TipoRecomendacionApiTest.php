<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TipoRecomendacion;

class TipoRecomendacionApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_tipo_recomendacion()
    {
        $tipoRecomendacion = factory(TipoRecomendacion::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/tipo_recomendacions', $tipoRecomendacion
        );

        $this->assertApiResponse($tipoRecomendacion);
    }

    /**
     * @test
     */
    public function test_read_tipo_recomendacion()
    {
        $tipoRecomendacion = factory(TipoRecomendacion::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/tipo_recomendacions/'.$tipoRecomendacion->id
        );

        $this->assertApiResponse($tipoRecomendacion->toArray());
    }

    /**
     * @test
     */
    public function test_update_tipo_recomendacion()
    {
        $tipoRecomendacion = factory(TipoRecomendacion::class)->create();
        $editedTipoRecomendacion = factory(TipoRecomendacion::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/tipo_recomendacions/'.$tipoRecomendacion->id,
            $editedTipoRecomendacion
        );

        $this->assertApiResponse($editedTipoRecomendacion);
    }

    /**
     * @test
     */
    public function test_delete_tipo_recomendacion()
    {
        $tipoRecomendacion = factory(TipoRecomendacion::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/tipo_recomendacions/'.$tipoRecomendacion->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/tipo_recomendacions/'.$tipoRecomendacion->id
        );

        $this->response->assertStatus(404);
    }
}
