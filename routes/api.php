<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\CapacitacionAPIController;
use App\Http\Controllers\API\AgenteAPIController;
use App\Http\Controllers\API\UserAPIController;
use App\Http\Controllers\API\DiagnosticoAPIController;
use App\Http\Controllers\API\ObservacionAPIController;
use App\Http\Controllers\API\Fhir\AddressController;
use App\Http\Controllers\API\Fhir\LocationController;
use App\Http\Controllers\API\Fhir\FacilityController;
use App\Http\Controllers\API\Fhir\ProviderController;
use App\Http\Controllers\API\Fhir\PatientController;
use App\Http\Controllers\API\Fhir\EncounterController;
use App\Http\Controllers\API\Fhir\ConditionController;
use App\Http\Controllers\API\Fhir\ObservationController;
use App\Http\Controllers\API\Fhir\DocumentReferenceController;
use App\Http\Controllers\API\InformeController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('user/login', ['as' => 'login', 'uses' => 'Auth\LoginController@getLogin']);
Route::post('user/login', 'Auth\LoginController@login')->middleware('web');
Route::post('user/logout', 'Auth\LoginController@logout');

//Route::get('user/instalar', 'Auth\LoginController@instalar');


Route::middleware('auth:sanctum')->get('/user/licencias', function (Request $request) {
    $roles = array(15, 18, 19, 3, 5, 27, 9, 28, 16, 8, 17, 22, 10, 25);
    $user = \App\User::where('idusuario', '=', $request->user()->idusuario)
        ->leftJoin('role_user', 'usuario.idusuario', 'role_user.user_id')
        ->leftJoin('roles', 'role_user.role_id', 'roles.id')
        ->leftJoin('teams', 'role_user.team_id', 'teams.id')
        ->where('teams.name', '=', 'licencias')
        ->select('roles.display_name', 'roles.name', 'teams.name as team_name')
        ->first();
    if (!empty($user)) {
        return [$request->user(), $user->display_name, $user->name, $user->team_name];
    } else {
        $user = \App\User::where('idusuario', '=', $request->user()->idusuario)
            ->leftJoin('role_user', 'usuario.idusuario', 'role_user.user_id')
            ->leftJoin('roles', 'role_user.role_id', 'roles.id')
            ->leftJoin('teams', 'role_user.team_id', 'teams.id')
            ->whereIn('role_user.role_id', $roles)
            ->select('roles.display_name', 'roles.name', 'teams.name as team_name')
            ->first();

        if (!empty($user)) {
            return [$request->user(), $user->display_name, $user->name, $user->team_name];
        } else {
            return false;
        }
    }
});

/*
Route::middleware('auth:sanctum')->post('/user/permiso', function (Request $request) {
    $idusuario = $request->get(0);
    $permiso = $request->get(1);
    $user = \App\User::where('idusuario','=',$idusuario)
        ->join('role_user', function ($join) {
            $join->on('role_user.user_id', '=', 'usuario.idusuario');})
        ->join('roles', function ($join) {
            $join->on('roles.id', '=', 'role_user.role_id');})
        ->join('roles_teams', function ($join) {
            $join->on('roles.id', '=', 'roles_teams.role_id');})
        ->join('teams', function ($join) {
            $join->on('teams.id', '=', 'roles_teams.team_id');})
        ->join('permission_role', function ($join) {
            $join->on('permission_role.role_id', '=', 'roles.id');})
        ->join('permission_role', function ($join) {
            $join->on('permission_role.role_id', '=', 'roles.id');})
        ->join('permissions', function ($join) {
            $join->on('permissions.id', '=', 'permission_role.permission_id');})
        ->where('teams.name','=','licencia')
        ->where('permissions.name','=',$permiso)
        ->first();
    if(!empty($user)){
        return true;
    }
    else{
        return false;
    }

});
*/
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('user/puestoValidado', 'UserAPIController@puestoValidado');
    Route::get('user/permisos/{cadena}', 'UserAPIController@permiso');
    Route::get('user/permisos', 'UserAPIController@permisos');
    Route::get('user/agente/{idusuario}', 'GrupoFamiliarAPIController@getAgenteUsuario');
    Route::get('agente/hijos/{documento?}/{hijos?}', 'GrupoFamiliarAPIController@getAgentesHijos');
    Route::get('agente/rapido', 'GrupoFamiliarAPIController@getAgentesRapido');
    Route::get('grupofamiliar/{logueado}/agente/{documento}', 'GrupoFamiliarAPIController@getAgente');
    Route::get('grupofamiliar/dependencia/{id}', 'GrupoFamiliarAPIController@getDependencias');
    Route::get('grupofamiliar/expediente/{idagente}', 'GrupoFamiliarAPIController@getExpedientes');
    Route::get('grupofamiliar/expediente/persona/{idexpediente}', 'GrupoFamiliarAPIController@getPersonasExpedientes');
    Route::post('grupofamiliar/complete', 'GrupoFamiliarAPIController@storeComplete');
    //Personas
    Route::get('personas/documento/{documento}', 'PersonaAPIController@getPersona');
    Route::get('personas/dias/{idpersona}/{idtipoLicencia}/{idlicencia}', 'PersonaAPIController@getDiasRestanteDiscapacitado');
    Route::get('personas/activos/{idagente}', 'PersonaAPIController@getPersonas');
    Route::get('personas/discapacitado/{idagente}/{documento}', 'PersonaAPIController@getDiscapacitado');
    Route::get('personas/expediente/{expediente}', 'PersonaAPIController@getPersonaPorExpediente');
    Route::get('personas/{idgrupoFamiliar}', 'PersonaAPIController@getPersonasGrupo');
    //
    //Licencias
    //licencias/dependiente/${obj.idagente}


    Route::get('licencias/personas/{idagente}', 'PersonaAPIController@getPersonasActivas');
    Route::get('licencias/personasDiscapacitada/{idagente}', 'PersonaAPIController@getPersonasDiscapacitadaActivas');
    Route::get('licencias/dias/{idagente}', 'LicenciaAPIController@getDiasPosiblesLicenciasAgentes');
    Route::get('licencias/feriados', 'LicenciaAPIController@getFeriados');
    Route::get('licencias/agente/{idagente}', 'LicenciaAPIController@getLicenciasPorAgenteTodas');
    Route::get('licencias/agente/{idagente}/{tipoLicencia}', 'LicenciaAPIController@getLicenciasPorAgente');
    Route::get('licencias/saldos/salud/personas/{idagente}', 'LicenciaAPIController@getSaldosPersonasSalud');

    Route::post('diagnosticos/licencia/{idlicencia}', [DiagnosticoAPIController::class, 'storeByLicencia']);
    Route::put('diagnosticos/licencia/{idlicencia}', [DiagnosticoAPIController::class, 'updateByLicencia']);
    Route::get('diagnosticos/licencia/{idlicencia}', [DiagnosticoAPIController::class, 'getByLicencia']);
    Route::get('diagnosticos/{idDiagnostico}/archivo', [DiagnosticoAPIController::class, 'getArchivoURL']);
    Route::get('observaciones/{idObservacion}/archivo', [DiagnosticoAPIController::class, 'getObservacionArchivoURL']);
    Route::get('tipo-licencias', 'LicenciaAPIController@fetchTipoLicencias');


    Route::get('licencias/consulta', 'LicenciaAPIController@getLicenciasConsulta');
    Route::get('licencias/consulta/mensual', 'LicenciaAPIController@getLicenciasMensual');
    Route::get('licencias/consulta/mensual/retroactiva', 'LicenciaAPIController@getLicenciasRetroactiva');
    Route::get('licencias/capacitaciones', 'LicenciaAPIController@licenciasCapacitaciones');
    Route::get('getCapacitacion/{idlicencia}', 'LicenciaAPIController@getCapacitacion');

    Route::put('licencias/exportar', 'LicenciaAPIController@exportLicencias');
    Route::get('licencias/familiar/{idagente}', 'LicenciaAPIController@getDiasLicenciasFamiliarAgentes');

    Route::post('licencias/complete', 'LicenciaAPIController@storeComplete');
    Route::get('licencias/dependiente', 'LicenciaAPIController@getLicenciasDependientes');
    Route::get('licencias/masivo/capacitacion', 'LicenciaAPIController@getLicenciasDependientesCapacitacion');


    Route::put('licencias/masivo/primer', 'LicenciaAPIController@primerVisadoTodo');
    Route::put('licencias/masivo/segundo', 'LicenciaAPIController@segundoVisadoTodo');
    Route::put('licencias/{idlicencia}', 'LicenciaAPIController@updateComplete');
    Route::put('licencias/desvisar/{idlicencia}', 'LicenciaAPIController@desvisar');


    Route::delete('licencias/{idlicencia}', 'LicenciaAPIController@destroy');

    Route::get('saldos/agente/{idagente}/{tipoLicencia}', 'LicenciaAPIController@getSaldosPorAgente');
    Route::get('saldos/agente/{idagente}', 'LicenciaAPIController@getSaldosPorAgenteTodas');
    Route::get('saldos/habiles/{idagente}', 'LicenciaAPIController@getSaldosHabilesPorAgente');


    //Licencias
    //Sanciones
    Route::get('sanciones/agente/{idagente}', 'SancionAPIController@getSancionesPorAgente');
    Route::get('sanciones/agente/exist/{idagente}', 'SancionAPIController@existSanciones');
    Route::get('sanciones/fetch', 'SancionAPIController@customQuery');
    Route::get('sanciones/{idsancion}', 'SancionAPIController@show');
    Route::post('sanciones', 'SancionAPIController@store');
    Route::put('sanciones/{idsancion}', 'SancionAPIController@update');
    Route::delete('sanciones/{idsancion}', 'SancionAPIController@destroy');
    //Sancioens




    //Organismos
    Route::get('organismos', 'OrganismoAPIController@index');
    //Organismos

    //Antiguedades
    Route::get('antiguedades/licencia/{idagente}/{idlicencia}', 'AntiguedadAPIController@getAntiguedadesMenosLicencia');
    Route::get('antiguedades/agente/{idagente}', 'AntiguedadAPIController@getAntiguedadesPorAgente');
    Route::get('antiguedades/consulta/mmk/ppp/fd', 'AntiguedadAPIController@getAntiguedadesConsulta');
    Route::get('antiguedades/{idantiguedad}', 'AntiguedadAPIController@show');
    Route::post('antiguedades', 'AntiguedadAPIController@store');
    Route::put('antiguedades/vigente', 'AntiguedadAPIController@updateVigente');
    Route::put('antiguedades/{idantiguedad}', 'AntiguedadAPIController@update');
    Route::delete('antiguedades/{idantiguedad}', 'AntiguedadAPIController@destroy');
    //Antiguedades

    //Importar excel con antiguedades
    Route::post('import-excel-personas', 'AntiguedadAPIController@importar');
    Route::post('import-excel-sanciones', 'SancionAPIController@importar');
    //

    //Dependencia
    //Route::get('dependencia/getPadres/{soloRed?}', 'DependenciaAPIController@vueSelectEfectores')->name('dependencia.vueSelectEfectores');
    Route::get('dependencia/consulta-lao/efectores/{iddependencia}', 'DependenciaAPIController@getHijasConsulta')->name('dependencia.getHijasConsulta');
    Route::get('dependencia/getHijas/{iddependencia}', 'DependenciaAPIController@vueSelectServicios')->name('dependencia.vueSelectServicios');

    Route::get('dependencia/dotacion/{idefector}/{idservicio}/{idtipo_funcion}/{idtipo_dia}/{hora_desde}/{hora_hasta}', 'DependenciaAPIController@getDotacion')->name('dependencia.getDotacion');
    Route::get('agentes/{id}', [AgenteAPIController::class, 'getDepartmentDetails']);

    //Dependencia

    Route::resource('tipoparentescos', 'TipoParentescoAPIController');
    Route::resource('licencias', 'LicenciaAPIController');
    Route::resource('personas', 'PersonaAPIController');
    Route::resource('licenciasfamiliar', 'LicenciaFamiliarAPIController');
    Route::resource('grupofamiliar', 'GrupoFamiliarAPIController');
    Route::resource('grupofamiliarpersona', 'GrupoFamiliarPersonaAPIController');
    Route::resource('tipolicencias', 'TipoLicenciaAPIController');
    Route::resource('agentes', 'AgenteAPIController');
    Route::get('agentes/documento/{dni}', 'AgenteAPIController@AgentePorDni');
    Route::get('agentes/capacitaciones/{idagente}', 'AgenteAPIController@getAgenteCapacitaciones');
    Route::get('user/{idusuario}/dependencias-visibles', [UserApiController::class, 'getDependenciasVisibles']);


    //Tipo Funcion
    Route::get('tipoFuncion', 'TipoFuncionAPIController@vueSelect')->name('tipoFuncion.vueSelect');
    //Tipo Funcion

    //AlcanceCapacitaciones
    Route::get('alcance', 'AlcanceCapacitacionAPIController@index');
    Route::get('alcance/{idAlcance}', 'AlcanceCapacitacionAPIController@show');
    Route::post('alcance', 'AlcanceCapacitacionAPIController@store');
    Route::put('alcance/{idAlcance}', 'AlcanceCapacitacionAPIController@update');
    Route::delete('alcance/{idAlcance}', 'AlcanceCapacitacionAPIController@destroy');
    //AlcanceCapacitaciones

    //TipoEvento
    Route::get('tipo-evento', 'TipoEventoAPIController@index');
    Route::get('tipo-evento/{idTipoEvento}', 'TipoEventoAPIController@show');
    Route::post('tipo-evento', 'TipoEventoAPIController@store');
    Route::put('tipo-evento/{idTipoEvento}', 'TipoEventoAPIController@update');
    Route::delete('tipo-evento/{idTipoEvento}', 'TipoEventoAPIController@destroy');
    //TipoEvento

    //Caracter
    Route::get('caracter', 'CaracterAPIController@index');
    Route::get('caracter/{idCaracter}', 'CaracterAPIController@show');
    Route::post('caracter', 'CaracterAPIController@store');
    Route::put('caracter/{idCaracter}', 'CaracterAPIController@update');
    Route::delete('caracter/{idCaracter}', 'CaracterAPIController@destroy');
    //Caracter

    //Capacitacion
    Route::get('capacitacion', 'CapacitacionAPIController@index');
    Route::get('capacitacion/{idCapacitacion}', 'CapacitacionAPIController@show');
    Route::get('capacitacion/agentes/{id}', 'CapacitacionAPIController@getCapacitacionAgentes');
    Route::post('capacitacion', 'CapacitacionAPIController@store');
    Route::post('capacitacion/{idCapacitacion}', 'CapacitacionAPIController@update');
    Route::delete('capacitacion/{idCapacitacion}', 'CapacitacionAPIController@destroy');
    //Capacitacion

});

// Rutas FHIR (fuera del grupo con namespace automático)
Route::middleware(['auth:sanctum'])->group(function () {
    // Address
    Route::resource('addresses', 'Fhir\AddressController');

    // Location
    Route::resource('locations', 'Fhir\LocationController');

    // Facility
    Route::resource('facilities', 'Fhir\FacilityController');

    // Provider
    Route::resource('providers', 'Fhir\ProviderController');
    Route::get('providers/search/agentes', [ProviderController::class, 'searchAgentes']);

    // Patient
    Route::resource('patients', 'Fhir\PatientController');

    // Encounter
    Route::resource('encounters', 'Fhir\EncounterController');

    // Condition
    Route::resource('conditions', 'Fhir\ConditionController');

    // Observation
    Route::resource('observations', 'Fhir\ObservationController');

    // DocumentReference
    Route::resource('document-references', 'Fhir\DocumentReferenceController');

    // Rutas de Informes
    Route::resource('informes', 'InformeController');
});

Route::post('capacitacion/agente/certificado', 'CapacitacionAPIController@uploadFile');
Route::get('capacitacion/agente/certificado/{filename}', 'CapacitacionAPIController@getFile');
Route::get('capacitacion/agente/getfilefromfolder/{folder}', 'CapacitacionAPIController@getFileFromFolder');
Route::post('capacitaciones/upload/programa/{idCapacitacion}', [CapacitacionApiController::class, 'uploadPrograma']);
Route::get('capacitaciones/programa/{idCapacitacion}', [CapacitacionApiController::class, 'getProgramaURL']);



Route::resource('capacitacion_agentes', 'CapacitacionAgenteAPIController');
