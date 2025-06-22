<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Models\Domicilio;
use App\Models\Localidad;
use App\Models\Departamento;
use App\Models\Provincia;


class DomicilioAPIController extends AppBaseController
{
    public function getProvincias()
    {
        $provincias = Provincia::orderBy('nombre')->get();
        $data = [];
        foreach($provincias as $key => $provincia) {
            $data[$key] = [
                'value' => $provincia->idprovincia,
                'label' => $provincia->nombre,
            ];
        }
        return response(['data' => $data],200);
    }

    public function getLocalidadesByProvincia($idprovincia)
    {
        $localidades = Localidad::getByProvincia($idprovincia);
        foreach($localidades as $key => $localidad) {
            $data[$key] = [
                'value' => $localidad->idlocalidad,
                'label' => $localidad->localidad,
            ];
        }
        return response(['data' => $data],200);
    }

    public function getDepartamentos(int $idprovincia=null)
    {
        if(isset($idprovincia)) {
            $departamentos = Departamento::where('idprovincia',$idprovincia)->orderBy('departamento')->get();
        } else {
            $departamentos = Departamento::orderBy('departamento')->get();
        }
        $data = [];
        foreach($departamentos as $key => $departamento) {
            $data[$key] = [
                'value' => $departamento->iddepartamento,
                'label' => $departamento->departamento,
            ];
        }

        return response($data,200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'calle' => 'required|string',
            'numero' => 'required|integer',
            'codigo_postal' => 'integer',
            'idlocalidad' => 'required|integer',
            'idprovincia' => 'required|integer',

        ]);

        $domicilio = Domicilio::create($request);
    }
}
