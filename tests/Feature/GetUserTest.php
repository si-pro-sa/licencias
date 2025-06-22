<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use App\User;
use App\Models\Antiguedad;
use Tests\TestCase;
use dd;

class GetUserTest extends TestCase
{
    //Permite utilizar la misma base de datos de modo transaccional sin tener que borrar o fabricar nada
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetUserApi()
    {

        //$user = factory(User::class)->create();

        $response = $this->call('POST', 'api/user/login', [
            'nombreusuario' => 'estela',
            'password' => 'daniel1'
        ]);


        $response = $this->call('GET', '/api/user/licencias');
        $response->assertStatus(200);
        $response->dumpSession();
        $response->dump();

    }


}
