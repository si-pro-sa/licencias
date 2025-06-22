<?php
namespace Tests\APIs;

// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;

use App\User;
use Tests\TestCase;
use Tests\ApiTestTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class GuardiaInterposicionHorariaTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware;

    protected $payload = null;
    protected $created = false;

    /*
    * NO CREA GUARDIAS
    */

    /* PERSONALIZADO */

    //Guardia día de semana -> horario personalizado
    /** @test */
    public function guardia_con_agente_horario_rotativo()
    {
        //Usuario
        $lmema = User::find(19);
        //Puesto activo GOMEZ, SILVIA INES 20277288
        //Puesto 08 a 20 con horario rotativo
        $idPuestoActivo = 64663;
        $fecha = '2021-12-13';
        $horaDesde = '08:00';
        $horaHasta = '20:00';
        $response = $this->actingAs($lmema)->json('GET', "/api/horario/existe-interposicion-horaria/{$idPuestoActivo}/{$fecha}/{$horaDesde}/{$horaHasta}");

        $response->assertStatus(201);
        $response->assertJson([
            'success' => false,
            'message' => 'Horario no interpuesto'
        ]);
    }
}
