<?php namespace Tests\Repositories;

use App\Models\TipoRecomendacion;
use App\Repositories\TipoRecomendacionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TipoRecomendacionRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TipoRecomendacionRepository
     */
    protected $tipoRecomendacionRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tipoRecomendacionRepo = \App::make(TipoRecomendacionRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_tipo_recomendacion()
    {
        $tipoRecomendacion = factory(TipoRecomendacion::class)->make()->toArray();

        $createdTipoRecomendacion = $this->tipoRecomendacionRepo->create($tipoRecomendacion);

        $createdTipoRecomendacion = $createdTipoRecomendacion->toArray();
        $this->assertArrayHasKey('id', $createdTipoRecomendacion);
        $this->assertNotNull($createdTipoRecomendacion['id'], 'Created TipoRecomendacion must have id specified');
        $this->assertNotNull(TipoRecomendacion::find($createdTipoRecomendacion['id']), 'TipoRecomendacion with given id must be in DB');
        $this->assertModelData($tipoRecomendacion, $createdTipoRecomendacion);
    }

    /**
     * @test read
     */
    public function test_read_tipo_recomendacion()
    {
        $tipoRecomendacion = factory(TipoRecomendacion::class)->create();

        $dbTipoRecomendacion = $this->tipoRecomendacionRepo->find($tipoRecomendacion->id);

        $dbTipoRecomendacion = $dbTipoRecomendacion->toArray();
        $this->assertModelData($tipoRecomendacion->toArray(), $dbTipoRecomendacion);
    }

    /**
     * @test update
     */
    public function test_update_tipo_recomendacion()
    {
        $tipoRecomendacion = factory(TipoRecomendacion::class)->create();
        $fakeTipoRecomendacion = factory(TipoRecomendacion::class)->make()->toArray();

        $updatedTipoRecomendacion = $this->tipoRecomendacionRepo->update($fakeTipoRecomendacion, $tipoRecomendacion->id);

        $this->assertModelData($fakeTipoRecomendacion, $updatedTipoRecomendacion->toArray());
        $dbTipoRecomendacion = $this->tipoRecomendacionRepo->find($tipoRecomendacion->id);
        $this->assertModelData($fakeTipoRecomendacion, $dbTipoRecomendacion->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_tipo_recomendacion()
    {
        $tipoRecomendacion = factory(TipoRecomendacion::class)->create();

        $resp = $this->tipoRecomendacionRepo->delete($tipoRecomendacion->id);

        $this->assertTrue($resp);
        $this->assertNull(TipoRecomendacion::find($tipoRecomendacion->id), 'TipoRecomendacion should not exist in DB');
    }
}
