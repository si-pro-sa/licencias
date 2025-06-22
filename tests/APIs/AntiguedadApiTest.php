<?php

namespace Tests\APIs;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Antiguedad;

class AntiguedadApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_antiguedad()
    {
        $antiguedad = factory(Antiguedad::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/antiguedades', $antiguedad
        );

        $this->assertApiResponse($antiguedad);
    }

    /**
     * @test
     */
    public function test_read_antiguedad()
    {
        $antiguedad = factory(Antiguedad::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/antiguedads/'.$antiguedad->id
        );

        $this->assertApiResponse($antiguedad->toArray());
    }

    /**
     * @test
     */
    public function test_update_antiguedad()
    {
        $antiguedad = factory(Antiguedad::class)->create();
        $editedAntiguedad = factory(Antiguedad::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/antiguedads/'.$antiguedad->id,
            $editedAntiguedad
        );

        $this->assertApiResponse($editedAntiguedad);
    }

    /**
     * @test
     */
    public function test_delete_antiguedad()
    {
        $antiguedad = factory(Antiguedad::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/antiguedads/'.$antiguedad->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/antiguedads/'.$antiguedad->id
        );

        $this->response->assertStatus(404);
    }

}
