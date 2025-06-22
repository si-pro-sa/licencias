<?php
namespace Tests\APIs;

use App\User;
use Tests\TestCase;
use Tests\ApiTestTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CargoCausalesTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware;

    /** @test */
    public function obtener_agente_reemplazado_cambio_funcion_para_cobertura_provisoria()
    {
        $aandrada = User::find(11);
        //Agente Reemplazado con Cambio de Función
        //Santillan, Guillermo Roque Javier
        $DNI = '29175421';
        $response = $this->actingAs($aandrada)
                        ->getJson("/api/agente/baja/datos/{$DNI}/19");
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'Datos de Agente'
        ]);
    }

    /** @test */
    public function aceptar_agente_reemplazado_jubilacion_para_cobertura_provisoria()
    {
        $aandrada = User::find(11);
        //Agente Reemplazado con Jubilación
        //APESTEY LUIS CESAR
        $DNI = '7370513';
        $response = $this->actingAs($aandrada)
                         ->getJson("/api/agente/baja/datos/{$DNI}/2");
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
            'message' => 'Datos de Agente'
        ]);
    }

    /** @test */
    public function rechazar_agente_reemplazado_jubilacion_para_cobertura_provisoria2()
    {
        $aandrada = User::find(11);
        //Agente Reemplazado con Jubilación
        //CLUA ANTONIO OCTAVIO
        $DNI = '8518450';
        $response = $this->actingAs($aandrada)
                         ->getJson("/api/agente/baja/datos/{$DNI}/2");
        $response->assertStatus(201);
        $response->assertJson([
            'success' => false,
            'message' => 'El Agente Reemplazado esta vinculado a una C Cargo'
        ]);
    }
}
