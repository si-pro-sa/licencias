<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Diagnostico;
use App\Models\Observacion;
use App\Models\Licencia;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Log;

class DiagnosticoAPIController extends AppBaseController
{
    /**
     * Listar todos los diagnósticos.
     */
    public function index()
    {
        $diagnosticos = Diagnostico::with(['observaciones', 'licencia', 'agente'])->get();
        return response()->json($diagnosticos, 200);
    }

    /**
     * Crear un nuevo diagnóstico.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idlicencia' => 'required|exists:licencias,idlicencia',
            'idagente' => 'required|exists:agente,idagente',
            'idObservacion' => 'required|exists:observaciones,idObservacion',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string',
            'codigo_icd10' => 'nullable|string|max:255',
            'archivo_url' => 'nullable|string|max:2048',
        ]);

        $diagnostico = Diagnostico::create($validated);

        return response()->json([
            'message' => 'Diagnóstico creado exitosamente',
            'diagnostico' => $diagnostico,
        ], 201);
    }

    /**
     * Mostrar un diagnóstico específico.
     */
    public function show($id)
    {
        $diagnostico = Diagnostico::with(['observaciones', 'licencia', 'agente'])->find($id);

        if (!$diagnostico) {
            return response()->json(['message' => 'Diagnóstico no encontrado'], 404);
        }

        return response()->json($diagnostico, 200);
    }

    /**
     * Actualizar un diagnóstico existente.
     */
    public function update(Request $request, $id)
    {
        $diagnostico = Diagnostico::find($id);

        if (!$diagnostico) {
            return response()->json(['message' => 'Diagnóstico no encontrado'], 404);
        }

        $validated = $request->validate([
            'idlicencia' => 'sometimes|exists:licencias,idlicencia',
            'idagente' => 'sometimes|exists:agente,idagente',
            'idObservacion' => 'sometimes|exists:observaciones,idObservacion',
            'fecha' => 'sometimes|date',
            'descripcion' => 'nullable|string',
            'codigo_icd10' => 'nullable|string|max:255',
            'archivo_url' => 'nullable|string|max:2048',
        ]);

        $diagnostico->update($validated);

        return response()->json([
            'message' => 'Diagnóstico actualizado exitosamente',
            'diagnostico' => $diagnostico,
        ], 200);
    }

    /**
     * Eliminar un diagnóstico.
     */
    public function destroy($id)
    {
        $diagnostico = Diagnostico::find($id);

        if (!$diagnostico) {
            return response()->json(['message' => 'Diagnóstico no encontrado'], 404);
        }

        $diagnostico->delete();

        return response()->json(['message' => 'Diagnóstico eliminado exitosamente'], 200);
    }

    /**
     * Obtener diagnósticos y observaciones asociadas por idlicencia.
     */
    public function getByLicencia($idlicencia)
    {
        // Obtener diagnósticos filtrados por idlicencia con sus observaciones
        $diagnosticos = Diagnostico::with('observaciones')
            ->where('idlicencia', $idlicencia)
            ->get();

        if ($diagnosticos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron diagnósticos para la licencia especificada'], 404);
        }

        return response()->json($diagnosticos, 200);
    }

    public function storeByLicencia(Request $request, $idlicencia)
    {

        $validated = $request->validate([
            'descripcion' => 'nullable|string',
            'codigo_icd10' => 'nullable|string|max:255',
            'archivo' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:2048',
            'observaciones' => 'nullable|array'
        ]);

        // Verificar que la licencia exista
        $licencia = Licencia::find($idlicencia);
        if (!$licencia) {
            return response()->json(['message' => 'Licencia no encontrada'], 404);
        }
        $now = new \DateTime();
        // Instanciar Firebase Storage
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/firebase.json');
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();


        // Procesar archivo del diagnóstico principal
        if ($request->hasFile('diagnostico.archivo')) {
            try {
                $file = $request->file('diagnostico.archivo');

                $name = time() . '_' . $file->getClientOriginalName();

                // Guardar temporalmente el archivo localmente
                $file->storeAs('/uploads', $name);
                $localFile = storage_path('app/uploads/' . $name);

                // Subir archivo a Firebase
                $fileContents = file_get_contents($localFile);
                $pathInBucket = 'diagnosticos/' . $idlicencia . '/' . $name;
                $bucket->upload($fileContents, ['name' => $pathInBucket]);

                // Eliminar archivo local
                unlink($localFile);

                // Guardar URL en el diagnóstico
                $validated['archivo_url'] = $pathInBucket;
            } catch (\Exception $e) {
                Log::error('Error al subir el archivo del diagnóstico: ' . $e->getMessage());
                return response()->json(['message' => 'Error al subir el archivo del diagnóstico.'], 500);
            }
        }

        // Crear el diagnóstico asociado a la licencia
        $diagnostico = Diagnostico::create([
            'idlicencia' => $idlicencia,
            'idagente' => $licencia['idagente'],
            'fecha' => $now,
            'descripcion' => $validated['descripcion'] ?? null,
            'codigo_icd10' => $validated['codigo_icd10'] ?? null,
            'archivo_url' => $validated['archivo_url'] ?? null,
        ]);

        // Procesar y guardar observaciones si existen
        if ($request->has('diagnostico')) {
            $diagnosticoData = $request->input('diagnostico');

            if (isset($diagnosticoData['observaciones']) && is_array($diagnosticoData['observaciones'])) {
                foreach ($diagnosticoData['observaciones'] as $key => $observacionData) {
                    try {
                        // Handle file uploads for observations
                        if ($request->hasFile("diagnostico.observaciones.$key.archivo")) {
                            $file = $request->file("diagnostico.observaciones.$key.archivo");
                            $name = time() . '_' . $file->getClientOriginalName();

                            // Save the file locally temporarily
                            $file->storeAs('/uploads', $name);
                            $localFile = storage_path('app/uploads/' . $name);

                            // Upload the file to Firebase
                            $fileContents = file_get_contents($localFile);
                            $pathInBucket = "observaciones/{$idlicencia}/{$diagnostico->idDiagnostico}/{$name}";
                            $bucket->upload($fileContents, ['name' => $pathInBucket]);

                            // Delete the local file
                            unlink($localFile);

                            // Store the file URL in the observation data
                            $observacionData['archivo_url'] = $pathInBucket;
                        }

                        // Save the observation
                        $observacion = new Observacion([
                            'idDiagnostico' => $diagnostico->idDiagnostico,
                            'fecha' => $observacionData['fecha'],
                            'tipo' => $observacionData['tipo'],
                            'descripcion' => $observacionData['descripcion'] ?? null,
                            'valor' => $observacionData['valor'] ?? null,
                            'unidad' => $observacionData['unidad'] ?? null,
                            'archivo_url' => $observacionData['archivo_url'] ?? null,
                            'sitio_estudio' => $observacionData['sitio_web'] ?? null,
                            'usuario' => $observacionData['usuario'] ?? null,
                            'clave' => $observacionData['contrasena'] ?? null,
                            'codigo' => $observacionData['codigo'] ?? null,
                        ]);

                        $diagnostico->observaciones()->save($observacion);
                    } catch (\Exception $e) {
                        Log::error("Error processing observation {$key}: " . $e->getMessage());
                    }
                }
            }
        }

        return response()->json([
            'message' => 'Diagnóstico y observaciones guardados exitosamente en Firebase',
            'diagnostico' => $diagnostico->load('observaciones'),
        ], 201);
    }

    public function updateByLicencia(Request $request, $idlicencia)
    {
        // Your current parsing is working fine, but let's store it in a variable
        $content = $request->getContent();
        $pattern = '/name="([^"]+)".*?\r\n\r\n(.*?)\r\n/s';
        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        $parsedData = [];
        foreach ($matches as $match) {
            $name = $match[1];
            $value = $match[2];

            if (strpos($name, '[') !== false) {
                preg_match('/([^\[]+)\[([^\]]+)\]/', $name, $parts);
                $parsedData[$parts[1]][$parts[2]] = $value;
            } else {
                $parsedData[$name] = $value;
            }
        }

        // Create a new request with the parsed data
        $request->merge($parsedData);

        // Now validate using the merged data
        $validated = $request->validate([
            'diagnostico.codigo' => 'nullable|string|max:255',
            'diagnostico.descripcion' => 'nullable|string',
            'diagnostico.archivo' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:2048',
            'diagnostico.observaciones' => 'nullable|array',
        ]);
        // Find the diagnostic associated with the given license ID
        $diagnostico = Diagnostico::where('idlicencia', $idlicencia)->first();

        if (!$diagnostico) {
            return response()->json(['message' => 'Diagnóstico no encontrado para esta licencia'], 404);
        }

        // Initialize Firebase Storage
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/firebase.json');
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();

        // Update diagnostic file if provided
        if ($request->hasFile('diagnostico.archivo')) {
            try {
                $file = $request->file('diagnostico.archivo');
                $name = time() . '_' . $file->getClientOriginalName();

                // Save file temporarily locally
                $file->storeAs('/uploads', $name);
                $localFile = storage_path('app/uploads/' . $name);

                // Upload file to Firebase
                $fileContents = file_get_contents($localFile);
                $pathInBucket = 'diagnosticos/' . $idlicencia . '/' . $name;
                $bucket->upload($fileContents, ['name' => $pathInBucket]);

                // Delete local file
                unlink($localFile);

                // Update diagnostic file URL in the database
                $validated['archivo_url'] = $pathInBucket;
            } catch (\Exception $e) {
                Log::error('Error al actualizar el archivo del diagnóstico: ' . $e->getMessage());
                return response()->json(['message' => 'Error al actualizar el archivo del diagnóstico.'], 500);
            }
        }

        // Update diagnostic details
        $diagnostico->update([
            'descripcion' => $validated['diagnostico']['descripcion'] ?? $diagnostico->descripcion,
            'codigo_icd10' => $validated['diagnostico']['codigo'] ?? $diagnostico->codigo_icd10,
            'archivo_url' => $validated['archivo_url'] ?? $diagnostico->archivo_url,
        ]);

        // Update or add observations
        if ($request->has('diagnostico.observaciones')) {
            $observaciones = $request->input('diagnostico.observaciones');

            foreach ($observaciones as $key => $observacionData) {
                try {
                    // Handle file uploads for observations
                    if ($request->hasFile("diagnostico.observaciones.$key.archivo")) {
                        $file = $request->file("diagnostico.observaciones.$key.archivo");
                        $name = time() . '_' . $file->getClientOriginalName();

                        // Save file locally temporarily
                        $file->storeAs('/uploads', $name);
                        $localFile = storage_path('app/uploads/' . $name);

                        // Upload file to Firebase
                        $fileContents = file_get_contents($localFile);
                        $pathInBucket = "observaciones/{$idlicencia}/{$diagnostico->idDiagnostico}/{$name}";
                        $bucket->upload($fileContents, ['name' => $pathInBucket]);

                        // Delete local file
                        unlink($localFile);

                        // Add the file URL to the observation data
                        $observacionData['archivo_url'] = $pathInBucket;
                    }

                    // Check if the observation exists and update or create it
                    if (isset($observacionData['id'])) {
                        // Update existing observation
                        $observacion = Observacion::find($observacionData['id']);
                        if ($observacion) {
                            $observacion->update([
                                'fecha' => $observacionData['fecha'] ?? $observacion->fecha,
                                'tipo' => $observacionData['tipo'] ?? $observacion->tipo,
                                'descripcion' => $observacionData['descripcion'] ?? $observacion->descripcion,
                                'valor' => $observacionData['valor'] ?? $observacion->valor,
                                'unidad' => $observacionData['unidad'] ?? $observacion->unidad,
                                'archivo_url' => $observacionData['archivo_url'] ?? $observacion->archivo_url,
                                'sitio_estudio' => $observacionData['sitio_web'] ?? $observacion->sitio_estudio,
                                'usuario' => $observacionData['usuario'] ?? $observacion->usuario,
                                'clave' => $observacionData['contrasena'] ?? $observacion->clave,
                                'codigo' => $observacionData['codigo'] ?? $observacion->codigo,
                            ]);
                        }
                    } else {
                        // Create a new observation
                        $newObservacion = new Observacion([
                            'idDiagnostico' => $diagnostico->idDiagnostico,
                            'fecha' => $observacionData['fecha'],
                            'tipo' => $observacionData['tipo'],
                            'descripcion' => $observacionData['descripcion'] ?? null,
                            'valor' => $observacionData['valor'] ?? null,
                            'unidad' => $observacionData['unidad'] ?? null,
                            'archivo_url' => $observacionData['archivo_url'] ?? null,
                            'sitio_estudio' => $observacionData['sitio_web'] ?? null,
                            'usuario' => $observacionData['usuario'] ?? null,
                            'clave' => $observacionData['contrasena'] ?? null,
                            'codigo' => $observacionData['codigo'] ?? null,
                        ]);

                        $diagnostico->observaciones()->save($newObservacion);
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing observation {$key}: " . $e->getMessage());
                }
            }
        }

        return response()->json([
            'message' => 'Diagnóstico y observaciones actualizados exitosamente en Firebase',
            'diagnostico' => $diagnostico->load('observaciones'),
        ], 200);
    }

    public function getArchivoURL($idDiagnostico)
    {
        // Buscar el diagnóstico por ID y verificar si tiene un archivo asociado
        $diagnostico = Diagnostico::find($idDiagnostico);

        if (!$diagnostico || !$diagnostico->archivo_url) {
            return response()->json(['success' => false, 'message' => 'Diagnóstico no encontrado o sin archivo asociado.'], 404);
        }

        try {
            // Instanciar Firebase Storage
            $factory = (new Factory)->withServiceAccount(__DIR__ . '/firebase.json');
            $storage = $factory->createStorage();
            $bucket = $storage->getBucket();

            // Buscar el archivo en el bucket
            $object = $bucket->object($diagnostico->archivo_url);
            if (!$object->exists()) {
                return response()->json(['success' => false, 'message' => 'El archivo no existe en Firebase.'], 404);
            }

            // Generar una URL firmada válida por 1 hora
            $signedUrl = $object->signedUrl(new \DateTime('+1 hour'), [
                'version' => 'v4',
            ]);

            return response()->json(['success' => true, 'url' => $signedUrl]);
        } catch (\Throwable $e) {
            // Manejar errores y registrar el problema
            Log::error('Error al generar la URL firmada: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al generar la URL del archivo.'], 500);
        }
    }
    public function getObservacionArchivoURL($idObservacion)
    {
        // Buscar la observación por ID y verificar si tiene un archivo asociado
        $observacion = Observacion::find($idObservacion);

        if (!$observacion || !$observacion->archivo_url) {
            return response()->json(['success' => false, 'message' => 'Observación no encontrada o sin archivo asociado.'], 404);
        }

        try {
            // Instanciar Firebase Storage
            $factory = (new Factory)->withServiceAccount(__DIR__ . '/firebase.json');
            $storage = $factory->createStorage();
            $bucket = $storage->getBucket();

            // Buscar el archivo en el bucket
            $object = $bucket->object($observacion->archivo_url);
            if (!$object->exists()) {
                return response()->json(['success' => false, 'message' => 'El archivo no existe en Firebase.'], 404);
            }

            // Generar una URL firmada válida por 1 hora
            $signedUrl = $object->signedUrl(new \DateTime('+1 hour'), [
                'version' => 'v4',
            ]);

            return response()->json(['success' => true, 'url' => $signedUrl]);
        } catch (\Throwable $e) {
            // Manejar errores y registrar el problema
            Log::error('Error al generar la URL firmada: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al generar la URL del archivo.'], 500);
        }
    }
}
