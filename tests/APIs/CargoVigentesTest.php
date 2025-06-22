<?php
namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\User;

class CargoVigentesTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware;

    /** @test */
    public function permitir_agente_reemplazado_jubilado_cargo_vacante_no_vigente()
    {
        $aandrada = User::find(11);

        //Agente Reemplazado
        //APESTEY LUIS CESAR
        $dniReemplazado = '7370513';
        //Jubilación
        $idtipo_cese = 2;
        $response = $this->actingAs($aandrada)
                        ->getJson("/api/agente/baja/datos/{$dniReemplazado}/{$idtipo_cese}");
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'success' => true,
        ]);

        //Agente Propuesto
        //RIOS OLGA VICENTA
        $dniPropuesto = '16806571';
        $response = $this->actingAs($aandrada)
                        ->getJson("/api/agente-candidato/datos/{$dniPropuesto}");
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'success' => true,
        ]);
    }

    /** @test */
    public function permitir_agente_reemplazado_fallecimiento_cargo_vacante_no_vigente_por_baja()
    {
        $aandrada = User::find(11);
        //Agente Reemplazado
        //SALAZAR VERGARAY OSCAR ANTONIO
        $dniReemplazado = '18811378';
        //Fallecimiento
        $idtipo_cese = 3;
        $response = $this->actingAs($aandrada)
                        ->getJson("/api/agente/baja/datos/{$dniReemplazado}/{$idtipo_cese}");
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'success' => true,
        ]);

        //Agente Propuesto
        //COPICHKA NADIA GABRIELA
        $dniPropuesto = '34327506';
        $response = $this->actingAs($aandrada)
                        ->getJson("/api/agente-candidato/datos/{$dniPropuesto}");
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'success' => true,
        ]);
    }

    /** @test */
    public function no_permitir_agente_reemplazado_jubilacion_cargo_vacante_vigente_por_vencimiento()
    {
        $aandrada = User::find(11);
        //Agente Reemplazado
        //CLUA ANTONIO OCTAVIO
        $dniReemplazado = '8518450';
        //Fallecimiento
        $idtipo_cese = 3;
        $response = $this->actingAs($aandrada)
                        ->getJson("/api/agente/baja/datos/{$dniReemplazado}/{$idtipo_cese}");
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'success' => false,
        ]);

        //Agente Propuesto
        //CASTILHO CORREIA CAMILA MEDICO
        $dniPropuesto = '95896304';
        $response = $this->actingAs($aandrada)
                        ->getJson("/api/agente-candidato/datos/{$dniPropuesto}");
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'success' => true,
        ]);
    }

    /** @test */
    public function no_permitir_agente_reemplazado_renuncia_cargo_vacante_vigente_por_baja()
    {
        $aandrada = User::find(11);
        //Agente Reemplazado
        //BARRANCO CARLOS FEDERICO
        $dniReemplazado = '27176183';
        //Fallecimiento
        $idtipo_cese = 3;
        $response = $this->actingAs($aandrada)
                        ->getJson("/api/agente/baja/datos/{$dniReemplazado}/{$idtipo_cese}");
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'success' => false,
        ]);

        //Agente Propuesto
        //AMADO ESCUDERO MARIA FELICITAS
        $dniPropuesto = '34132083';
        $response = $this->actingAs($aandrada)
                        ->getJson("/api/agente-candidato/datos/{$dniPropuesto}");
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'success' => true,
        ]);
    }
}
