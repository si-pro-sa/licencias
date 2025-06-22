<?php namespace Tests\Repositories;

use App\Models\EvaluacionPsicotecnicaGrupo;
use App\Repositories\EvaluacionPsicotecnicaGrupoRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class EvaluacionPsicotecnicaGrupoRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var EvaluacionPsicotecnicaGrupoRepository
     */
    protected $evaluacionPsicotecnicaGrupoRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->evaluacionPsicotecnicaGrupoRepo = \App::make(EvaluacionPsicotecnicaGrupoRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_evaluacion_psicotecnica_grupo()
    {
        $evaluacionPsicotecnicaGrupo = factory(EvaluacionPsicotecnicaGrupo::class)->make()->toArray();

        $createdEvaluacionPsicotecnicaGrupo = $this->evaluacionPsicotecnicaGrupoRepo->create($evaluacionPsicotecnicaGrupo);

        $createdEvaluacionPsicotecnicaGrupo = $createdEvaluacionPsicotecnicaGrupo->toArray();
        $this->assertArrayHasKey('id', $createdEvaluacionPsicotecnicaGrupo);
        $this->assertNotNull($createdEvaluacionPsicotecnicaGrupo['id'], 'Created EvaluacionPsicotecnicaGrupo must have id specified');
        $this->assertNotNull(EvaluacionPsicotecnicaGrupo::find($createdEvaluacionPsicotecnicaGrupo['id']), 'EvaluacionPsicotecnicaGrupo with given id must be in DB');
        $this->assertModelData($evaluacionPsicotecnicaGrupo, $createdEvaluacionPsicotecnicaGrupo);
    }

    /**
     * @test read
     */
    public function test_read_evaluacion_psicotecnica_grupo()
    {
        $evaluacionPsicotecnicaGrupo = factory(EvaluacionPsicotecnicaGrupo::class)->create();

        $dbEvaluacionPsicotecnicaGrupo = $this->evaluacionPsicotecnicaGrupoRepo->find($evaluacionPsicotecnicaGrupo->id);

        $dbEvaluacionPsicotecnicaGrupo = $dbEvaluacionPsicotecnicaGrupo->toArray();
        $this->assertModelData($evaluacionPsicotecnicaGrupo->toArray(), $dbEvaluacionPsicotecnicaGrupo);
    }

    /**
     * @test update
     */
    public function test_update_evaluacion_psicotecnica_grupo()
    {
        $evaluacionPsicotecnicaGrupo = factory(EvaluacionPsicotecnicaGrupo::class)->create();
        $fakeEvaluacionPsicotecnicaGrupo = factory(EvaluacionPsicotecnicaGrupo::class)->make()->toArray();

        $updatedEvaluacionPsicotecnicaGrupo = $this->evaluacionPsicotecnicaGrupoRepo->update($fakeEvaluacionPsicotecnicaGrupo, $evaluacionPsicotecnicaGrupo->id);

        $this->assertModelData($fakeEvaluacionPsicotecnicaGrupo, $updatedEvaluacionPsicotecnicaGrupo->toArray());
        $dbEvaluacionPsicotecnicaGrupo = $this->evaluacionPsicotecnicaGrupoRepo->find($evaluacionPsicotecnicaGrupo->id);
        $this->assertModelData($fakeEvaluacionPsicotecnicaGrupo, $dbEvaluacionPsicotecnicaGrupo->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_evaluacion_psicotecnica_grupo()
    {
        $evaluacionPsicotecnicaGrupo = factory(EvaluacionPsicotecnicaGrupo::class)->create();

        $resp = $this->evaluacionPsicotecnicaGrupoRepo->delete($evaluacionPsicotecnicaGrupo->id);

        $this->assertTrue($resp);
        $this->assertNull(EvaluacionPsicotecnicaGrupo::find($evaluacionPsicotecnicaGrupo->id), 'EvaluacionPsicotecnicaGrupo should not exist in DB');
    }
}
