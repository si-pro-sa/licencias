<?php

namespace App\Http\Controllers\Api\Fhir;

use App\Http\Controllers\Controller;
use App\Models\FhirProvider;
use App\Models\Agente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * FHIR Provider Controller for managing healthcare providers
 */
class ProviderController extends Controller
{
    /**
     * Display a listing of providers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $providers = FhirProvider::with(['agente', 'facility', 'address'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($provider) {
                    $name = json_decode($provider->name, true);
                    $qualification = json_decode($provider->qualification, true);
                    $telecom = json_decode($provider->telecom, true);

                    // Extract phone and email from telecom
                    $phone = null;
                    $email = null;
                    if (is_array($telecom)) {
                        foreach ($telecom as $contact) {
                            if (isset($contact['system']) && $contact['system'] === 'phone') {
                                $phone = $contact['value'] ?? null;
                            }
                            if (isset($contact['system']) && $contact['system'] === 'email') {
                                $email = $contact['value'] ?? null;
                            }
                        }
                    }

                    return [
                        'id' => $provider->fhir_provider_id,
                        'fhir_id' => $provider->fhir_id,
                        'es_agente' => !is_null($provider->idagente),
                        'agente_id' => $provider->idagente,
                        'nombre' => $name['given'][0] ?? '',
                        'apellido' => $name['family'] ?? '',
                        'nombre_completo' => ($name['family'] ?? '') . ', ' . ($name['given'][0] ?? ''),
                        'dni' => $name['identifier'] ?? null,
                        'tipo_prestador' => $qualification[0]['code']['display'] ?? 'No especificado',
                        'matricula' => $qualification[0]['identifier'][0]['value'] ?? null,
                        'telefono' => $phone,
                        'email' => $email,
                        'genero' => $provider->gender,
                        'fecha_nacimiento' => $provider->birth_date,
                        'establecimiento' => $provider->facility ? $provider->facility->name : null,
                        'activo' => $provider->active,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $providers
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching providers: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener prestadores'
            ], 500);
        }
    }

    /**
     * Store a newly created provider
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'es_agente' => 'required|boolean',
            'agente_id' => 'required_if:es_agente,true|exists:agentes,idagente',
            'nombre' => 'required_if:es_agente,false|string|max:255',
            'apellido' => 'required_if:es_agente,false|string|max:255',
            'dni' => 'required_if:es_agente,false|string|max:10',
            'tipo_prestador' => 'required|string|max:100',
            'matricula' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'genero' => 'nullable|in:male,female,other,unknown',
            'fecha_nacimiento' => 'nullable|date',
            'establecimiento_id' => 'nullable|exists:fhir_facilities,fhir_facility_id',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Prepare FHIR name structure
            $name = [
                'use' => 'official',
                'family' => $request->apellido,
                'given' => [$request->nombre],
                'identifier' => $request->dni
            ];

            // Prepare FHIR qualification structure
            $qualification = [];
            if ($request->tipo_prestador || $request->matricula) {
                $qualification[] = [
                    'code' => [
                        'coding' => [[
                            'system' => 'http://terminology.hl7.org/CodeSystem/practitioner-role',
                            'code' => Str::slug($request->tipo_prestador),
                            'display' => $request->tipo_prestador
                        ]]
                    ],
                    'identifier' => $request->matricula ? [
                        ['system' => 'matricula', 'value' => $request->matricula]
                    ] : []
                ];
            }

            // Prepare FHIR telecom structure
            $telecom = [];
            if ($request->telefono) {
                $telecom[] = [
                    'system' => 'phone',
                    'value' => $request->telefono,
                    'use' => 'work'
                ];
            }
            if ($request->email) {
                $telecom[] = [
                    'system' => 'email',
                    'value' => $request->email,
                    'use' => 'work'
                ];
            }

            $provider = FhirProvider::create([
                'fhir_id' => Str::uuid(),
                'active' => $request->activo ?? true,
                'name' => json_encode($name),
                'gender' => $request->genero,
                'birth_date' => $request->fecha_nacimiento,
                'qualification' => !empty($qualification) ? json_encode($qualification) : null,
                'telecom' => !empty($telecom) ? json_encode($telecom) : null,
                'fhir_facility_id' => $request->establecimiento_id,
                'idagente' => $request->es_agente ? $request->agente_id : null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Prestador creado exitosamente',
                'data' => $provider
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating provider: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear prestador'
            ], 500);
        }
    }

    /**
     * Display the specified provider
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $provider = FhirProvider::with(['agente', 'facility', 'address'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $provider
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching provider: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Prestador no encontrado'
            ], 404);
        }
    }

    /**
     * Update the specified provider
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'es_agente' => 'boolean',
            'agente_id' => 'required_if:es_agente,true|exists:agentes,idagente',
            'nombre' => 'required_if:es_agente,false|string|max:255',
            'apellido' => 'required_if:es_agente,false|string|max:255',
            'dni' => 'required_if:es_agente,false|string|max:10',
            'tipo_prestador' => 'string|max:100',
            'matricula' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'genero' => 'nullable|in:male,female,other,unknown',
            'fecha_nacimiento' => 'nullable|date',
            'establecimiento_id' => 'nullable|exists:fhir_facilities,fhir_facility_id',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $provider = FhirProvider::findOrFail($id);

            // Update name if provided
            if ($request->has('nombre') || $request->has('apellido')) {
                $name = json_decode($provider->name, true) ?? [];
                if ($request->has('apellido')) $name['family'] = $request->apellido;
                if ($request->has('nombre')) $name['given'] = [$request->nombre];
                if ($request->has('dni')) $name['identifier'] = $request->dni;
                $provider->name = json_encode($name);
            }

            // Update qualification if provided
            if ($request->has('tipo_prestador') || $request->has('matricula')) {
                $qualification = json_decode($provider->qualification, true) ?? [];
                if (empty($qualification)) {
                    $qualification[] = ['code' => [], 'identifier' => []];
                }

                if ($request->has('tipo_prestador')) {
                    $qualification[0]['code'] = [
                        'coding' => [[
                            'system' => 'http://terminology.hl7.org/CodeSystem/practitioner-role',
                            'code' => Str::slug($request->tipo_prestador),
                            'display' => $request->tipo_prestador
                        ]]
                    ];
                }

                if ($request->has('matricula')) {
                    $qualification[0]['identifier'] = [
                        ['system' => 'matricula', 'value' => $request->matricula]
                    ];
                }

                $provider->qualification = json_encode($qualification);
            }

            // Update telecom if provided
            if ($request->has('telefono') || $request->has('email')) {
                $telecom = [];
                if ($request->telefono) {
                    $telecom[] = [
                        'system' => 'phone',
                        'value' => $request->telefono,
                        'use' => 'work'
                    ];
                }
                if ($request->email) {
                    $telecom[] = [
                        'system' => 'email',
                        'value' => $request->email,
                        'use' => 'work'
                    ];
                }
                $provider->telecom = json_encode($telecom);
            }

            // Update other fields
            if ($request->has('genero')) $provider->gender = $request->genero;
            if ($request->has('fecha_nacimiento')) $provider->birth_date = $request->fecha_nacimiento;
            if ($request->has('establecimiento_id')) $provider->fhir_facility_id = $request->establecimiento_id;
            if ($request->has('activo')) $provider->active = $request->activo;
            if ($request->has('es_agente')) {
                $provider->idagente = $request->es_agente ? $request->agente_id : null;
            }

            $provider->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Prestador actualizado exitosamente',
                'data' => $provider
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating provider: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar prestador'
            ], 500);
        }
    }

    /**
     * Remove the specified provider
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $provider = FhirProvider::findOrFail($id);
            $provider->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Prestador eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting provider: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar prestador'
            ], 500);
        }
    }

    /**
     * Search agents for provider assignment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchAgentes(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        try {
            $query = $request->input('q');

            $agentes = Agente::where(function ($q) use ($query) {
                $q->where('nombre', 'LIKE', "%{$query}%")
                    ->orWhere('apellido', 'LIKE', "%{$query}%")
                    ->orWhere('documento', 'LIKE', "%{$query}%");
            })
                ->where('activo', true)
                ->limit(20)
                ->get()
                ->map(function ($agente) {
                    return [
                        'id' => $agente->idagente,
                        'nombre' => $agente->nombre,
                        'apellido' => $agente->apellido,
                        'nombre_completo' => $agente->apellido . ', ' . $agente->nombre,
                        'dni' => $agente->documento,
                        'cargo' => $agente->cargo ?? 'Sin cargo'
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $agentes
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching agents: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar agentes'
            ], 500);
        }
    }
}
