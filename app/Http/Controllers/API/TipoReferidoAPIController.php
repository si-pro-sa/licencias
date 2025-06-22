<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

use App\Models\TipoReferido;

class TipoReferidoAPIController extends AppBaseController
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
            'organismo' => 'required|string',
            'nombre' => 'required|string',
            'interno' => 'required|boolean',
        ]);

        $referido = new TipoReferido();
        $referido->organismo = $request->organismo;
        $referido->nombre = $request->nombre;
        $referido->telefono = $request->telefono;
        $referido->interno = $request->interno;

        if($referido->save()){
            $data = [
                'success' => true,
                'message' => 'Referido guardado correctamente.',
            ];
            return response()->json(['data' => $data, 201]);
        } else {
            $data = [
                'success' => false,
                'message' => '*Error al guardar, verifique los campos obligatorios.',
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

    public function listar($isInterno)
    {
        if($isInterno==1) {
            $referidos = TipoReferido::where('interno', true)->orderBy('nombre')->get();
        } else {
            $referidos = TipoReferido::where('interno', false)->orderBy('nombre')->get();
        }

        if(!count($referidos)>0) {
            $data = [
                'success' => false,
                'message' => 'No se encontro ningún resultado',
            ];
            return response($data);
        }

        $data = [];
        foreach ($referidos as $key => $referido) {
            $data[$key] = [
                'value' => $referido->idtipo_referido,
                'label' => $referido->nombre .' - '. $referido->organismo,
            ];
        }
        return response(['data' => $data],200);
    }
}
