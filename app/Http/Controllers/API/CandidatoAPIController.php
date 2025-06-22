<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Domicilio;
use App\Models\RecomendacionCandidato;
use Illuminate\Http\Request;
use App\Models\Candidato;
use Illuminate\Support\Facades\DB;

class CandidatoAPIController extends AppBaseController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        $this->validate($request, [
            'documento' => 'required',
            'cuil' => 'required',
            'apellido' => 'required|string',
            'nombre' => 'required|string',
            'fnacimiento' => 'required|date',
            'telefono' => 'string|nullable',
            'celular' => 'required|string',
            'email' => 'required|email',
            'idtitulo' => 'required|integer',
            'idfuncion' => 'required|integer',
            'idnivel' => 'required|integer',
        ]);

        $candidato = new Candidato();
        $candidato->documento = $request->documento;
        $candidato->cuil = $request->cuil;
        $candidato->apellido = $request->apellido;
        $candidato->nombre = $request->nombre;
        $candidato->fnacimiento = $request->fnacimiento;
        $candidato->telefono = $request->telefono;
        $candidato->celular = $request->celular;
        $candidato->email = $request->email;


        DB::beginTransaction();
        $domicilio = Domicilio::create($request->domicilio);
        if(!isset($domicilio)) {
            DB::rollback();
            $data = [
                'success' => false,
                'message' => '*Error al guardar el candidato, verifique los campos obligatorios.',
            ];
            return response()->json(['data' => $data, 422]);
        }
        $candidato->iddomicilio = $domicilio->iddomicilio;
        if($candidato->save()) {
            $recomendacion = new RecomendacionCandidato();
            $recomendacion->candidato_id = $candidato->idcandidato;
            $recomendacion->candidato_type = get_class($candidato);
            $recomendacion->idtipo_funcion = $request->idfuncion;
            $recomendacion->idtitulo = $request->idtitulo;
            $recomendacion->idtipo_nivel = $request->idnivel;
            $recomendacion->idtipo_referido_interno = $request->referido_interno;
            $recomendacion->idtipo_referido_politico = $request->referido_externo;

            if($recomendacion->save()) {
                DB::commit();
                $data = [
                    'success' => true,
                    'message' => 'Candidato guardado correctamente.',
                ];
                return response()->json(['data' => $data, 201]);
            } else {
                DB::rollBack();
                $data = [
                    'success' => false,
                    'message' => '*Error al guardar el candidato, verifique los campos obligatorios.',
                ];
                return response()->json(['data' => $data, 422]);
            }

        } else {
            DB::rollBack();
            $data = [
                'success' => false,
                'message' => '*Error al guardar el candidato, verifique los campos obligatorios.',
            ];
            return response()->json(['data' => $data, 422]);
        }
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateContactData(Request $request, $id)
    {
        $this->validate($request, [
            'telefono' => 'string',
            'celular' => 'required|string',
            'email' => 'required|email',
        ]);

        $candidato = Candidato::findOrFail($id);
        $candidato->telefono = $request->telefono;
        $candidato->celular = $request->celular;
        $candidato->email = $request->email;

        DB::beginTransaction();
        $domicilio = Domicilio::create($request->domicilio);

        if(!isset($domicilio)) {
            DB::rollback();
            $data = [
                'success' => false,
                'message' => '*Error al actualizar el candidato, verifique los campos obligatorios.',
            ];
            return response()->json(['data' => $data, 422]);
        }

        $candidato->iddomicilio = $domicilio->iddomicilio;
        if($candidato->save()) {
            DB::commit();
            $data = [
                'success' => true,
                'message' => 'Información de contacto actualizada correctamente.',
            ];
            return response()->json(['data' => $data, 201]);
        } else {
            DB::rollBack();
            $data = [
                'success' => false,
                'message' => '*Error al actualizar el candidato, verifique los campos obligatorios.',
            ];
            return response()->json(['data' => $data, 422]);
        }

    }
}
