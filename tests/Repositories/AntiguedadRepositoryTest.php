<?php namespace Tests\Repositories;

use App\Models\Antiguedad;
use App\Repositories\AntiguedadRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AntiguedadRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var AntiguedadRepository
     */
    protected $antiguedadRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->antiguedadRepo = \App::make(AntiguedadRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_antiguedad()
    {
        $antiguedad = factory(Antiguedad::class)->make()->toArray();

        $createdAntiguedad = $this->antiguedadRepo->create($antiguedad);

        $createdAntiguedad = $createdAntiguedad->toArray();
        $this->assertArrayHasKey('idantiguedad', $createdAntiguedad);
        $this->assertNotNull($createdAntiguedad['idantiguedad'], 'Created Antiguedad must have id specified');
        $this->assertNotNull(Antiguedad::find($createdAntiguedad['idantiguedad']), 'Antiguedad with given id must be in DB');
        $this->assertModelData($antiguedad, $createdAntiguedad);
    }

    /**
     * @test read
     */
    public function test_read_antiguedad()
    {
        $antiguedad = factory(Antiguedad::class)->create();

        $dbAntiguedad = $this->antiguedadRepo->find($antiguedad->idantiguedad);

        $dbAntiguedad = $dbAntiguedad->toArray();
        $this->assertModelData($antiguedad->toArray(), $dbAntiguedad);
    }

    /**
     * @test update
     */
    public function test_update_antiguedad()
    {
        $antiguedad = factory(Antiguedad::class)->create();
        $fakeAntiguedad = factory(Antiguedad::class)->make()->toArray();

        $updatedAntiguedad = $this->antiguedadRepo->update($fakeAntiguedad, $antiguedad->idantiguedad);

        $this->assertModelData($fakeAntiguedad, $updatedAntiguedad->toArray());
        $dbAntiguedad = $this->antiguedadRepo->find($antiguedad->idantiguedad);
        $this->assertModelData($fakeAntiguedad, $dbAntiguedad->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_antiguedad()
    {
        $antiguedad = factory(Antiguedad::class)->create();

        $resp = $this->antiguedadRepo->delete($antiguedad->idantiguedad);

        $this->assertTrue($resp);
        $this->assertNull(Antiguedad::find($antiguedad->idantiguedad), 'Antiguedad should not exist in DB');
    }
}
