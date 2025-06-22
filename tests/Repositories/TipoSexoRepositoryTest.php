<?php namespace Tests\Repositories;

use App\Models\TipoSexo;
use App\Repositories\TipoSexoRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TipoSexoRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TipoSexoRepository
     */
    protected $tipoSexoRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tipoSexoRepo = \App::make(TipoSexoRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_tipo_sexo()
    {
        $tipoSexo = factory(TipoSexo::class)->make()->toArray();

        $createdTipoSexo = $this->tipoSexoRepo->create($tipoSexo);

        $createdTipoSexo = $createdTipoSexo->toArray();
        $this->assertArrayHasKey('id', $createdTipoSexo);
        $this->assertNotNull($createdTipoSexo['id'], 'Created TipoSexo must have id specified');
        $this->assertNotNull(TipoSexo::find($createdTipoSexo['id']), 'TipoSexo with given id must be in DB');
        $this->assertModelData($tipoSexo, $createdTipoSexo);
    }

    /**
     * @test read
     */
    public function test_read_tipo_sexo()
    {
        $tipoSexo = factory(TipoSexo::class)->create();

        $dbTipoSexo = $this->tipoSexoRepo->find($tipoSexo->id);

        $dbTipoSexo = $dbTipoSexo->toArray();
        $this->assertModelData($tipoSexo->toArray(), $dbTipoSexo);
    }

    /**
     * @test update
     */
    public function test_update_tipo_sexo()
    {
        $tipoSexo = factory(TipoSexo::class)->create();
        $fakeTipoSexo = factory(TipoSexo::class)->make()->toArray();

        $updatedTipoSexo = $this->tipoSexoRepo->update($fakeTipoSexo, $tipoSexo->id);

        $this->assertModelData($fakeTipoSexo, $updatedTipoSexo->toArray());
        $dbTipoSexo = $this->tipoSexoRepo->find($tipoSexo->id);
        $this->assertModelData($fakeTipoSexo, $dbTipoSexo->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_tipo_sexo()
    {
        $tipoSexo = factory(TipoSexo::class)->create();

        $resp = $this->tipoSexoRepo->delete($tipoSexo->id);

        $this->assertTrue($resp);
        $this->assertNull(TipoSexo::find($tipoSexo->id), 'TipoSexo should not exist in DB');
    }
}
