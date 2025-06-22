<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Observacion;
use App\Http\Controllers\AppBaseController;

class ObservacionAPIController extends AppBaseController
{
    /**
     * Listar todas las observaciones.
     */
    public function index()
    {
        $observaciones = Observacion::all();
        return response()->json($observaciones, 200);
    }

    /**
     * Crear una nueva observación.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'valor' => 'nullable|string|max:255',
            'unidad' => 'nullable|string|max:255',
            'archivo_url' => 'nullable|string|max:2048',
            'sitio_estudio' => 'nullable|url',
            'usuario' => 'nullable|string|max:255',
            'clave' => 'nullable|string|max:255',
            'codigo' => 'nullable|string|max:255',
        ]);

        $observacion = Observacion::create($validated);

        return response()->json([
            'message' => 'Observación creada exitosamente',
            'observacion' => $observacion,
        ], 201);
    }

    /**
     * Mostrar una observación específica.
     */
    public function show($id)
    {
        $observacion = Observacion::find($id);

        if (!$observacion) {
            return response()->json(['message' => 'Observación no encontrada'], 404);
        }

        return response()->json($observacion, 200);
    }

    /**
     * Actualizar una observación existente.
     */
    public function update(Request $request, $id)
    {
        $observacion = Observacion::find($id);

        if (!$observacion) {
            return response()->json(['message' => 'Observación no encontrada'], 404);
        }

        $validated = $request->validate([
            'fecha' => 'sometimes|date',
            'tipo' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'valor' => 'nullable|string|max:255',
            'unidad' => 'nullable|string|max:255',
            'archivo_url' => 'nullable|string|max:2048',
            'sitio_estudio' => 'nullable|url',
            'usuario' => 'nullable|string|max:255',
            'clave' => 'nullable|string|max:255',
            'codigo' => 'nullable|string|max:255',
        ]);

        $observacion->update($validated);

        return response()->json([
            'message' => 'Observación actualizada exitosamente',
            'observacion' => $observacion,
        ], 200);
    }

    /**
     * Eliminar una observación.
     */
    public function destroy($id)
    {
        $observacion = Observacion::find($id);

        if (!$observacion) {
            return response()->json(['message' => 'Observación no encontrada'], 404);
        }

        $observacion->delete();

        return response()->json(['message' => 'Observación eliminada exitosamente'], 200);
    }
}
