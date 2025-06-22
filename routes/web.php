<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();
//Esta ruta tiene nombre porque el middleware authenticate te manda a la view con ese nombre si bien se puede cambiar no lo hacemos

Route::get('/', function () {
    return view('home');
})->name('login');

Route::get('/login', function () {
    return view('home');
});
Route::middleware('auth:sanctum')->get('/{any}', function () {
    return view('home');
})->where('any', '.*');

/*
Route::get('/login', function () {
    return view('auth/login');
});
Route::get('/', function () {
    return view('auth/login');
});
Route::get('/app', function () {
    return view('home');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/prueba', function () {
    return view('welcome');
});
*/


Route::resource('caracters', 'CaracterController');

Route::resource('tipoEventos', 'TipoEventoController');

Route::resource('alcanceCapacitacions', 'AlcanceCapacitacionController');

Route::resource('capacitacions', 'CapacitacionController');

Route::resource('capacitacionAgentes', 'CapacitacionAgenteController');

Route::resource('capacitacionAgentes', 'CapacitacionAgenteController');

Route::resource('capacitacionAgentes', 'CapacitacionAgenteController');

Route::get('/listaAgentes', 'AgenteController@listAgentes')->name('agentes_lista');

Route::get('/sendmail', function () {
    $data = [
        'name' => 'Prueba de envio de email',
    ];

    Mail::send('welcome', $data, function ($message) {
        $message->from('testings@delgadopetrino.com.ar', 'Prueba desde LARAVEL');

        $message->to('pablo@delgadopetrino.com.ar')->subject('testing desde laravel');
    });
    return 'email enviado correctamente';
});

//Route::get('/grupoFamiliar', 'GrupoFamiliar@index')->name('grupo_familiar');
Route::group(['middleware' => 'cors'], function () {
    Route::get('/grupoFamiliar', function () {
        return view('grupo_familiar.index');
    });
    Route::get('/grupoFamiliar/{idagente}/create', function () {
        return view('grupo_familiar.create');
    });
    Route::get('/licencia/{idagente}/create', function () {
        return view('licencia.create');
    });
    Route::get('/licencia', function () {
        return view('licencia.index');
    });
    Route::get('/sancion', function () {
        return view('sancion.index');
    });
    Route::get('/antiguedades', function () {
        return view('antiguedades.index');
    });
});
Route::resource('personas', 'PersonaController');
Route::resource('licencias', 'LicenciaController');
Route::resource('licenciasFamiliar', 'LicenciaFamiliarController');
Route::resource('grupofamiliar', 'GrupoFamiliarController');
Route::resource('grupofamiliarpersona', 'GrupoFamiliarPersonaController');
Route::resource('tipolicencias', 'TipoLicenciaController');
Route::resource('sancions', 'SancionController');

Route::resource('usuarios', 'UsuarioController');

Route::resource('ldAltas', 'LdAltaController');

Route::resource('ldCodigos', 'LdCodigoController');

Route::resource('ldCambioEstados', 'LdCambioEstadoController');

Route::resource('guardias', 'GuardiaController');

Route::resource('guardiaLineas', 'GuardiaLineaController');

Route::resource('reemplazos', 'ReemplazoController');

Route::resource('tipoCampanias', 'TipoCampaniaController');
