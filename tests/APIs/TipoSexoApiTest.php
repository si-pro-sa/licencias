<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\TipoSexo;

class TipoSexoApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_tipo_sexo()
    {
        $tipoSexo = factory(TipoSexo::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/tipo_sexos', $tipoSexo
        );

        $this->assertApiResponse($tipoSexo);
    }

    /**
     * @test
     */
    public function test_read_tipo_sexo()
    {
        $tipoSexo = factory(TipoSexo::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/tipo_sexos/'.$tipoSexo->id
        );

        $this->assertApiResponse($tipoSexo->toArray());
    }

    /**
     * @test
     */
    public function test_update_tipo_sexo()
    {
        $tipoSexo = factory(TipoSexo::class)->create();
        $editedTipoSexo = factory(TipoSexo::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/tipo_sexos/'.$tipoSexo->id,
            $editedTipoSexo
        );

        $this->assertApiResponse($editedTipoSexo);
    }

    /**
     * @test
     */
    public function test_delete_tipo_sexo()
    {
        $tipoSexo = factory(TipoSexo::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/tipo_sexos/'.$tipoSexo->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/tipo_sexos/'.$tipoSexo->id
        );

        $this->response->assertStatus(404);
    }
}
