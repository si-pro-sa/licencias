<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\EvaluacionPsicotecnicaGrupo;

class EvaluacionPsicotecnicaGrupoApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_evaluacion_psicotecnica_grupo()
    {
        $evaluacionPsicotecnicaGrupo = factory(EvaluacionPsicotecnicaGrupo::class)->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/evaluacion_psicotecnica_grupos', $evaluacionPsicotecnicaGrupo
        );

        $this->assertApiResponse($evaluacionPsicotecnicaGrupo);
    }

    /**
     * @test
     */
    public function test_read_evaluacion_psicotecnica_grupo()
    {
        $evaluacionPsicotecnicaGrupo = factory(EvaluacionPsicotecnicaGrupo::class)->create();

        $this->response = $this->json(
            'GET',
            '/api/evaluacion_psicotecnica_grupos/'.$evaluacionPsicotecnicaGrupo->id
        );

        $this->assertApiResponse($evaluacionPsicotecnicaGrupo->toArray());
    }

    /**
     * @test
     */
    public function test_update_evaluacion_psicotecnica_grupo()
    {
        $evaluacionPsicotecnicaGrupo = factory(EvaluacionPsicotecnicaGrupo::class)->create();
        $editedEvaluacionPsicotecnicaGrupo = factory(EvaluacionPsicotecnicaGrupo::class)->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/evaluacion_psicotecnica_grupos/'.$evaluacionPsicotecnicaGrupo->id,
            $editedEvaluacionPsicotecnicaGrupo
        );

        $this->assertApiResponse($editedEvaluacionPsicotecnicaGrupo);
    }

    /**
     * @test
     */
    public function test_delete_evaluacion_psicotecnica_grupo()
    {
        $evaluacionPsicotecnicaGrupo = factory(EvaluacionPsicotecnicaGrupo::class)->create();

        $this->response = $this->json(
            'DELETE',
             '/api/evaluacion_psicotecnica_grupos/'.$evaluacionPsicotecnicaGrupo->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/evaluacion_psicotecnica_grupos/'.$evaluacionPsicotecnicaGrupo->id
        );

        $this->response->assertStatus(404);
    }
}
