<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Informe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InformeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $informes = Informe::with(['licencia', 'encounter', 'documentReference', 'provider'])->get();
        return response()->json($informes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entidadExaminadora' => 'required|string|max:255',
            'fechaExamen' => 'required|date',
            'apellidoNombre' => 'required|string|max:255',
            'dni' => 'required|string|max:20',
            'reparticionOrigen' => 'required|string|max:255',
            'modalidadAtencion' => 'required|in:CONSULTORIO,JUNTA_MEDICA',
            'diasJustificar' => 'required|integer|min:1',
            'articulo' => 'nullable|string|max:255',
            'fechaDesde' => 'required|date',
            'fechaHasta' => 'required|date|after_or_equal:fechaDesde',
            'fechaAlta' => 'nullable|date|after_or_equal:fechaHasta',
            'fechaControl' => 'nullable|date|after_or_equal:fechaAlta',
            'observaciones' => 'nullable|string',
            'domicilio' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $informe = Informe::create([
                'numero_informe' => $this->generateNumeroInforme(),
                'fecha_emision' => now(),
                'fecha_evaluacion' => $request->fechaExamen,
                'tipo_informe' => $request->modalidadAtencion,
                'contenido' => json_encode([
                    'entidadExaminadora' => $request->entidadExaminadora,
                    'apellidoNombre' => $request->apellidoNombre,
                    'dni' => $request->dni,
                    'reparticionOrigen' => $request->reparticionOrigen,
                    'diasJustificar' => $request->diasJustificar,
                    'articulo' => $request->articulo,
                    'fechaDesde' => $request->fechaDesde,
                    'fechaHasta' => $request->fechaHasta,
                    'fechaAlta' => $request->fechaAlta,
                    'fechaControl' => $request->fechaControl,
                    'observaciones' => $request->observaciones,
                    'domicilio' => $request->domicilio
                ]),
                'estado' => 'PENDIENTE'
            ]);

            return response()->json([
                'message' => 'Informe creado exitosamente',
                'data' => $informe
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el informe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $informe = Informe::with(['licencia', 'encounter', 'documentReference', 'provider'])->findOrFail($id);
        return response()->json($informe);
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
        $validator = Validator::make($request->all(), [
            'entidadExaminadora' => 'required|string|max:255',
            'fechaExamen' => 'required|date',
            'apellidoNombre' => 'required|string|max:255',
            'dni' => 'required|string|max:20',
            'reparticionOrigen' => 'required|string|max:255',
            'modalidadAtencion' => 'required|in:CONSULTORIO,JUNTA_MEDICA',
            'diasJustificar' => 'required|integer|min:1',
            'articulo' => 'nullable|string|max:255',
            'fechaDesde' => 'required|date',
            'fechaHasta' => 'required|date|after_or_equal:fechaDesde',
            'fechaAlta' => 'nullable|date|after_or_equal:fechaHasta',
            'fechaControl' => 'nullable|date|after_or_equal:fechaAlta',
            'observaciones' => 'nullable|string',
            'domicilio' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $informe = Informe::findOrFail($id);
            $informe->update([
                'fecha_evaluacion' => $request->fechaExamen,
                'tipo_informe' => $request->modalidadAtencion,
                'contenido' => json_encode([
                    'entidadExaminadora' => $request->entidadExaminadora,
                    'apellidoNombre' => $request->apellidoNombre,
                    'dni' => $request->dni,
                    'reparticionOrigen' => $request->reparticionOrigen,
                    'diasJustificar' => $request->diasJustificar,
                    'articulo' => $request->articulo,
                    'fechaDesde' => $request->fechaDesde,
                    'fechaHasta' => $request->fechaHasta,
                    'fechaAlta' => $request->fechaAlta,
                    'fechaControl' => $request->fechaControl,
                    'observaciones' => $request->observaciones,
                    'domicilio' => $request->domicilio
                ])
            ]);

            return response()->json([
                'message' => 'Informe actualizado exitosamente',
                'data' => $informe
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el informe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $informe = Informe::findOrFail($id);
            $informe->delete();

            return response()->json([
                'message' => 'Informe eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el informe',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera un número de informe único
     *
     * @return string
     */
    private function generateNumeroInforme()
    {
        $year = date('Y');
        $lastInforme = Informe::where('numero_informe', 'like', "INF-{$year}%")
            ->orderBy('numero_informe', 'desc')
            ->first();

        if (!$lastInforme) {
            return "INF-{$year}-0001";
        }

        $lastNumber = intval(substr($lastInforme->numero_informe, -4));
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "INF-{$year}-{$newNumber}";
    }
}
