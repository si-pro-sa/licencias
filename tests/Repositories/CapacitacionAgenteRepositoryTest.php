<?php namespace Tests\Repositories;

use App\Models\CapacitacionAgente;
use App\Repositories\CapacitacionAgenteRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CapacitacionAgenteRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var CapacitacionAgenteRepository
     */
    protected $capacitacionAgenteRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->capacitacionAgenteRepo = \App::make(CapacitacionAgenteRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_capacitacion_agente()
    {
        $capacitacionAgente = factory(CapacitacionAgente::class)->make()->toArray();

        $createdCapacitacionAgente = $this->capacitacionAgenteRepo->create($capacitacionAgente);

        $createdCapacitacionAgente = $createdCapacitacionAgente->toArray();
        $this->assertArrayHasKey('id', $createdCapacitacionAgente);
        $this->assertNotNull($createdCapacitacionAgente['id'], 'Created CapacitacionAgente must have id specified');
        $this->assertNotNull(CapacitacionAgente::find($createdCapacitacionAgente['id']), 'CapacitacionAgente with given id must be in DB');
        $this->assertModelData($capacitacionAgente, $createdCapacitacionAgente);
    }

    /**
     * @test read
     */
    public function test_read_capacitacion_agente()
    {
        $capacitacionAgente = factory(CapacitacionAgente::class)->create();

        $dbCapacitacionAgente = $this->capacitacionAgenteRepo->find($capacitacionAgente->id);

        $dbCapacitacionAgente = $dbCapacitacionAgente->toArray();
        $this->assertModelData($capacitacionAgente->toArray(), $dbCapacitacionAgente);
    }

    /**
     * @test update
     */
    public function test_update_capacitacion_agente()
    {
        $capacitacionAgente = factory(CapacitacionAgente::class)->create();
        $fakeCapacitacionAgente = factory(CapacitacionAgente::class)->make()->toArray();

        $updatedCapacitacionAgente = $this->capacitacionAgenteRepo->update($fakeCapacitacionAgente, $capacitacionAgente->id);

        $this->assertModelData($fakeCapacitacionAgente, $updatedCapacitacionAgente->toArray());
        $dbCapacitacionAgente = $this->capacitacionAgenteRepo->find($capacitacionAgente->id);
        $this->assertModelData($fakeCapacitacionAgente, $dbCapacitacionAgente->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_capacitacion_agente()
    {
        $capacitacionAgente = factory(CapacitacionAgente::class)->create();

        $resp = $this->capacitacionAgenteRepo->delete($capacitacionAgente->id);

        $this->assertTrue($resp);
        $this->assertNull(CapacitacionAgente::find($capacitacionAgente->id), 'CapacitacionAgente should not exist in DB');
    }
}
