<?php namespace Tests\Repositories;

use App\Models\TipoEntrevista;
use App\Repositories\TipoEntrevistaRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TipoEntrevistaRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TipoEntrevistaRepository
     */
    protected $tipoEntrevistaRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tipoEntrevistaRepo = \App::make(TipoEntrevistaRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_tipo_entrevista()
    {
        $tipoEntrevista = factory(TipoEntrevista::class)->make()->toArray();

        $createdTipoEntrevista = $this->tipoEntrevistaRepo->create($tipoEntrevista);

        $createdTipoEntrevista = $createdTipoEntrevista->toArray();
        $this->assertArrayHasKey('id', $createdTipoEntrevista);
        $this->assertNotNull($createdTipoEntrevista['id'], 'Created TipoEntrevista must have id specified');
        $this->assertNotNull(TipoEntrevista::find($createdTipoEntrevista['id']), 'TipoEntrevista with given id must be in DB');
        $this->assertModelData($tipoEntrevista, $createdTipoEntrevista);
    }

    /**
     * @test read
     */
    public function test_read_tipo_entrevista()
    {
        $tipoEntrevista = factory(TipoEntrevista::class)->create();

        $dbTipoEntrevista = $this->tipoEntrevistaRepo->find($tipoEntrevista->id);

        $dbTipoEntrevista = $dbTipoEntrevista->toArray();
        $this->assertModelData($tipoEntrevista->toArray(), $dbTipoEntrevista);
    }

    /**
     * @test update
     */
    public function test_update_tipo_entrevista()
    {
        $tipoEntrevista = factory(TipoEntrevista::class)->create();
        $fakeTipoEntrevista = factory(TipoEntrevista::class)->make()->toArray();

        $updatedTipoEntrevista = $this->tipoEntrevistaRepo->update($fakeTipoEntrevista, $tipoEntrevista->id);

        $this->assertModelData($fakeTipoEntrevista, $updatedTipoEntrevista->toArray());
        $dbTipoEntrevista = $this->tipoEntrevistaRepo->find($tipoEntrevista->id);
        $this->assertModelData($fakeTipoEntrevista, $dbTipoEntrevista->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_tipo_entrevista()
    {
        $tipoEntrevista = factory(TipoEntrevista::class)->create();

        $resp = $this->tipoEntrevistaRepo->delete($tipoEntrevista->id);

        $this->assertTrue($resp);
        $this->assertNull(TipoEntrevista::find($tipoEntrevista->id), 'TipoEntrevista should not exist in DB');
    }
}
