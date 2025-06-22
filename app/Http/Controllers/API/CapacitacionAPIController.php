<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCapacitacionAPIRequest;
use App\Http\Requests\API\UpdateCapacitacionAPIRequest;
use App\Models\Capacitacion;
use App\Repositories\CapacitacionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Storage;
use Response;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Class CapacitacionController
 * @package App\Http\Controllers\API
 */

class CapacitacionAPIController extends AppBaseController
{
    /** @var  CapacitacionRepository */
    private $capacitacionRepository;
    private $storage;
    private $bucket;

    public function __construct(CapacitacionRepository $capacitacionRepo)
    {
        $this->capacitacionRepository = $capacitacionRepo;
        //$factory = (new Factory)->withServiceAccount(config('firebase.service_account'));
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/' . 'firebase.json');
        $this->storage = $factory->createStorage();
        $this->bucket = $this->storage->getBucket();
    }

    /**
     * Display a listing of the Capacitacion.
     * GET|HEAD /capacitacions
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        // Cargar todas las capacitaciones con tipo de evento y alcance
        $capacitaciones = Capacitacion::with('tipoEvento', 'alcanceCapacitacion')->get();

        // Aplanar la estructura para incluir la descripción de tipo de evento y alcance al mismo nivel
        $capacitaciones = $capacitaciones->map(function ($capacitacion) {
            $capacitacion->tipo_evento = $capacitacion->tipoEvento->descripcion ?? null;
            $capacitacion->alcance = $capacitacion->alcanceCapacitacion->descripcion ?? null;

            // Opcional: elimina los objetos de relación si no los necesitas
            unset($capacitacion->tipoEvento, $capacitacion->alcanceCapacitacion);

            return $capacitacion;
        });
        return $this->sendResponse($capacitaciones->toArray(), 'Capacitaciones retrieved successfully');
    }

    /**
     * Store a newly created Capacitacion in storage.
     * POST /capacitacions
     *
     * @param CreateCapacitacionAPIRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'resolucion' => 'nullable|string',
            'razon' => 'nullable|string',
            'evento_nombre' => 'required|string|max:255',
            'evento_lugar' => 'required|string|max:255',
            'fecha_evento_inicio' => 'required|date',
            'fecha_evento_final' => 'required|date',
            'idTipoEvento' => 'required|integer|exists:tipo_evento,idTipoEvento',  // Ensure the tipo_evento ID exists
            'idAlcanceCapacitacion' => 'required|integer|exists:alcance_capacitacion,idAlcanceCapacitacion',  // Ensure the alcance_capacitacion ID exists
            'file' => 'nullable|file|max:10240' // max file size of 10 MB
        ]);

        // Initialize input with validated data
        $input = $validatedData;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = time() . '_' . $file->getClientOriginalName();
            $env = config('app.env'); // Get the current environment
            $prefix = config("app.env_prefix.$env", 'dev'); // Retrieve the prefix based on the environment
            $path = $prefix . '/' . $request->input('idagente') . '/capacitacion/programa' . $name;
            $input['programa'] = $path;

            // Upload directly to Firebase Storage
            $uploadedFile = $this->bucket->upload(file_get_contents($file->getRealPath()), [
                'name' => $path
            ]);
        }

        $capacitacion = $this->capacitacionRepository->create($input);
        return $this->sendResponse($capacitacion->toArray(), 'Capacitacion saved successfully with file.');
    }

    /**
     * Display the specified Capacitacion.
     * GET|HEAD /capacitacions/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Capacitacion $capacitacion */
        $capacitacion = $this->capacitacionRepository->find($id);

        if (empty($capacitacion)) {
            return $this->sendError('Capacitacion not found');
        }

        return $this->sendResponse($capacitacion->toArray(), 'Capacitacion retrieved successfully');
    }

    /**
     * Update the specified Capacitacion in storage.
     * PUT/PATCH /capacitacions/{id}
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        Log::info('Headers:', $request->headers->all());
        Log::info('Request Body:', ['body' => $request->getContent()]); // Log raw body data
        Log::info('Raw Input', ['input' => $request->all()]);


        // Fetch the existing Capacitacion
        $capacitacion = $this->capacitacionRepository->find($id);
        if (empty($capacitacion)) {
            return $this->sendError('Capacitacion not found');
        }

        // Validate the incoming request data
        $validatedData = $request->validate([
            'resolucion' => 'nullable|string',
            'razon' => 'nullable|string',
            'evento_nombre' => 'sometimes|required|string|max:255',
            'evento_lugar' => 'sometimes|required|string|max:255',
            'fecha_evento_inicio' => 'sometimes|required|date',
            'fecha_evento_final' => 'sometimes|required|date',
            'idTipoEvento' => 'required|integer|exists:tipo_evento,idTipoEvento',  // Ensure the tipo_evento ID exists
            'idAlcanceCapacitacion' => 'required|integer|exists:alcance_capacitacion,idAlcanceCapacitacion',
            'file' => 'nullable|file|max:10240' // max file size of 10 MB
        ]);

        // Check if there's a file to update
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = time() . '_' . $file->getClientOriginalName();
            $env = config('app.env'); // Get the current environment
            $prefix = config("app.env_prefix.$env", 'dev'); // Retrieve the prefix based on the environment
            $path = $prefix . '/' . $request->input('idagente') . '/capacitacion/programa' . $name;
            $validatedData['programa'] = $path;  // Update the file path in the 'programa' field

            // Upload directly to Firebase Storage
            $uploadedFile = $this->bucket->upload(file_get_contents($file->getRealPath()), [
                'name' => $path
            ]);
        }

        // Update the Capacitacion with validated data
        $capacitacion = $this->capacitacionRepository->update($validatedData, $id);

        return $this->sendResponse($capacitacion->toArray(), 'Capacitacion updated successfully.');
    }

    /**
     * Remove the specified Capacitacion from storage.
     * DELETE /capacitacions/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Capacitacion $capacitacion */
        $capacitacion = $this->capacitacionRepository->find($id);

        if (empty($capacitacion)) {
            return $this->sendError('Capacitacion not found');
        }

        $capacitacion->delete();

        return $this->sendSuccess('Capacitacion deleted successfully');
    }

    /**
     * Remove the specified Capacitacion from storage.
     * GET /capacitacions/agente
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function getCapacitacionAgentes($id)
    {
        /** @var Capacitacion $capacitacion */
        $capacitacion = $this->capacitacionRepository->find($id);

        if (empty($capacitacion)) {
            return $this->sendError('Capacitacion not found');
        }

        $agentes = Capacitacion::leftJoin('capacitacion_agente as ca', 'capacitacion.idCapacitacion', '=', 'ca.idCapacitacion')
            ->leftJoin('agente as ag', 'ca.idAgente', '=', 'ag.idagente')
            ->leftJoin('alcance_capacitacion as alca', 'alca.idAlcanceCapacitacion', '=', 'capacitacion.idAlcanceCapacitacion')
            ->leftJoin('tipo_evento as tev', 'tev.idTipoEvento', '=', 'capacitacion.idTipoEvento')
            ->selectRaw('capacitacion.*, ag.*, tev.descripcion as tipo_evento, alca.descripcion as alcance')
            ->where('ca.idCapacitacion', '=', $id)->get();

        return $this->sendResponse($agentes->toArray(), 'Capacitacion updated successfully');
    }

    public function uploadFile(Request $request)
    {
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/' . 'firebase.json');
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();
        $agenteId = $request->get('agenteId');
        $capacitacionId = $request->get('capacitacionId');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('/uploads', $name);

            $localFile = storage_path('app/uploads/' . $name);
            $fileContents = file_get_contents($localFile);

            $pathInBucket = $agenteId . '/capacitacion/certificados/' . $capacitacionId;
            $uploadedFile = $bucket->upload($fileContents, [
                'name' => $pathInBucket
            ]);

            // Eliminar el archivo local después de subirlo a Firebase
            unlink($localFile);

            return response()->json(['success' => true, 'path' => $pathInBucket]);
        }

        return response()->json(['success' => false, 'message' => 'No se pudo subir el archivo.']);
    }

    /**
     * Retrieve a pre-signed URL for a file in Firebase Storage.
     *
     * @param string $filename The name of the file in Firebase Storage.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFile($filename)
    {
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/' . 'firebase.json');
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();
        $object = $bucket->object($filename);
        if ($object->exists()) {
            $signed = $object->signedUrl(new \DateTime('+1 hour')); // URL válida por 1 hora
            return response()->json(['success' => true, 'url' => $signed]);
        }
        return response()->json(['success' => false, 'message' => 'El archivo no existe.']);
    }

    /**
     * Retrieve a pre-signed URL for the first file in a specific Firebase Storage folder.
     *
     * @param string $folder The folder path in Firebase Storage.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFileFromFolder($folder)
    {
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/' . 'firebase.json');
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();

        // List objects in the specified folder and get the first one
        $objects = $bucket->objects(['prefix' => $folder . '/capacitacion/certificados/']);
        $firstObject = null;

        foreach ($objects as $object) {
            $firstObject = $object;
            break;
        }

        if ($firstObject) {
            // Generate a signed URL for the first file in the folder
            $signed = $firstObject->signedUrl(new \DateTime('+1 hour')); // URL válida por 1 hora
            return response()->json(['success' => true, 'url' => $signed]);
        }

        return response()->json(['success' => false, 'message' => 'No hay archivos en la carpeta especificada.']);
    }

    public function uploadPrograma($idCapacitacion, Request $request)
    {
        $factory = (new Factory)->withServiceAccount(__DIR__ . '/' . 'firebase.json');
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();
        $capacitacionId = $request->input('idCapacitacion'); // Utilizar input en lugar de get
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $name = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('/uploads', $name);

            $localFile = storage_path('app/uploads/' . $name);
            $fileContents = file_get_contents($localFile);

            $pathInBucket = 'capacitaciones/' . $capacitacionId . '/programas/' . $name;
            $bucket->upload($fileContents, ['name' => $pathInBucket]);

            // Eliminar el archivo local después de subirlo a Firebase
            unlink($localFile);

            // Actualizar el atributo 'programa' en la capacitación correspondiente
            $capacitacion = Capacitacion::find($capacitacionId);
            if ($capacitacion) {
                $capacitacion->programa = $pathInBucket;
                $capacitacion->save();
            }

            return response()->json(['success' => true, 'path' => $pathInBucket]);
        }

        return $this->sendError('No se pudo subir el archivo.');
    }


    public function getProgramaURL($idCapacitacion)
    {
        // Ensure the Capacitacion ID is valid and fetch the relevant data
        $capacitacion = Capacitacion::find($idCapacitacion);
        if (!$capacitacion || !$capacitacion->programa) {
            return response()->json(['success' => false, 'message' => 'Capacitación no encontrada o sin programa asociado.'], 404);
        }

        try {
            $object = $this->bucket->object($capacitacion->programa);
            if (!$object->exists()) {
                return response()->json(['success' => false, 'message' => 'El archivo no existe.'], 404);
            }

            // Generate a signed URL that is valid for 1 hour
            $signedUrl = $object->signedUrl(new \DateTime('+1 hour'), [
                'version' => 'v4'
            ]);

            return response()->json(['success' => true, 'url' => $signedUrl]);
        } catch (Throwable $e) {
            // Log the error or handle it as per the application's requirements
            Log::error('Error generating signed URL: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al generar URL.'], 500);
        }
    }
}
