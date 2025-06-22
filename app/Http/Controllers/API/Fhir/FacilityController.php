<?php

namespace App\Http\Controllers\Api\Fhir;

use App\Http\Controllers\Controller;
use App\Models\FhirFacility;
use App\Models\FhirAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * FHIR Facility Controller for managing healthcare facilities
 */
class FacilityController extends Controller
{
    /**
     * Display a listing of facilities
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $facilities = FhirFacility::with(['address', 'location'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($facility) {
                    $name = json_decode($facility->name, true);
                    $type = json_decode($facility->type, true);
                    $telecom = json_decode($facility->telecom, true);
                    $alias = json_decode($facility->alias, true);

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

                    // Get address details
                    $address = $facility->address;

                    return [
                        'id' => $facility->fhir_facility_id,
                        'fhir_id' => $facility->fhir_id,
                        'nombre' => is_string($name) ? $name : ($name['text'] ?? 'Sin nombre'),
                        'codigo_sisa' => $alias[0] ?? null,
                        'tipo_establecimiento' => $type[0]['coding'][0]['display'] ?? 'No especificado',
                        'nivel_atencion' => $type[0]['coding'][0]['code'] ?? 1,
                        'direccion' => $address ? $address->line1 . ' ' . $address->line2 : '',
                        'provincia' => $address ? $address->state : '',
                        'localidad' => $address ? $address->city : '',
                        'codigo_postal' => $address ? $address->postal_code : '',
                        'telefono' => $phone,
                        'email' => $email,
                        'responsable' => null, // This field doesn't exist in FHIR model
                        'servicios' => [], // This would need to be implemented differently
                        'descripcion' => $facility->description,
                        'activo' => $facility->status === 'active',
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $facilities
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching facilities: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener establecimientos'
            ], 500);
        }
    }

    /**
     * Store a newly created facility
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_sisa' => 'nullable|string|max:50',
            'tipo_establecimiento' => 'required|string|max:100',
            'nivel_atencion' => 'required|integer|between:1,3',
            'direccion' => 'required|string|max:255',
            'provincia' => 'required|string|max:100',
            'localidad' => 'required|string|max:100',
            'codigo_postal' => 'nullable|string|max:10',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'responsable' => 'nullable|string|max:255',
            'servicios' => 'nullable|array',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Create address first
            $address = null;
            if ($request->direccion) {
                $address = FhirAddress::create([
                    'fhir_id' => Str::uuid(),
                    'use' => 'work',
                    'type' => 'physical',
                    'line1' => $request->direccion,
                    'city' => $request->localidad,
                    'state' => $request->provincia,
                    'postal_code' => $request->codigo_postal,
                    'country' => 'AR'
                ]);
            }

            // Prepare FHIR name structure
            $name = [
                'text' => $request->nombre,
                'use' => 'official'
            ];

            // Prepare FHIR type structure
            $type = [[
                'coding' => [[
                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-RoleCode',
                    'code' => (string)$request->nivel_atencion,
                    'display' => $request->tipo_establecimiento
                ]]
            ]];

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

            // Prepare alias (for SISA code)
            $alias = [];
            if ($request->codigo_sisa) {
                $alias[] = $request->codigo_sisa;
            }

            $facility = FhirFacility::create([
                'fhir_id' => Str::uuid(),
                'status' => $request->activo ? 'active' : 'inactive',
                'name' => json_encode($name),
                'alias' => !empty($alias) ? json_encode($alias) : null,
                'description' => $request->observaciones,
                'type' => json_encode($type),
                'telecom' => !empty($telecom) ? json_encode($telecom) : null,
                'fhir_address_id' => $address ? $address->fhir_address_id : null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Establecimiento creado exitosamente',
                'data' => $facility
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating facility: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear establecimiento'
            ], 500);
        }
    }

    /**
     * Display the specified facility
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $facility = FhirFacility::with(['address', 'location'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $facility
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching facility: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Establecimiento no encontrado'
            ], 404);
        }
    }

    /**
     * Update the specified facility
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'string|max:255',
            'codigo_sisa' => 'nullable|string|max:50',
            'tipo_establecimiento' => 'string|max:100',
            'nivel_atencion' => 'integer|between:1,3',
            'direccion' => 'string|max:255',
            'provincia' => 'string|max:100',
            'localidad' => 'string|max:100',
            'codigo_postal' => 'nullable|string|max:10',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'responsable' => 'nullable|string|max:255',
            'servicios' => 'nullable|array',
            'observaciones' => 'nullable|string',
            'activo' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $facility = FhirFacility::findOrFail($id);

            // Update address if provided
            if ($request->has('direccion') || $request->has('localidad') || $request->has('provincia')) {
                if ($facility->fhir_address_id) {
                    $address = FhirAddress::find($facility->fhir_address_id);
                    if ($address) {
                        if ($request->has('direccion')) $address->line1 = $request->direccion;
                        if ($request->has('localidad')) $address->city = $request->localidad;
                        if ($request->has('provincia')) $address->state = $request->provincia;
                        if ($request->has('codigo_postal')) $address->postal_code = $request->codigo_postal;
                        $address->save();
                    }
                } else {
                    // Create new address
                    $address = FhirAddress::create([
                        'fhir_id' => Str::uuid(),
                        'use' => 'work',
                        'type' => 'physical',
                        'line1' => $request->direccion ?? '',
                        'city' => $request->localidad ?? '',
                        'state' => $request->provincia ?? '',
                        'postal_code' => $request->codigo_postal ?? '',
                        'country' => 'AR'
                    ]);
                    $facility->fhir_address_id = $address->fhir_address_id;
                }
            }

            // Update name if provided
            if ($request->has('nombre')) {
                $name = json_decode($facility->name, true) ?? [];
                $name['text'] = $request->nombre;
                $facility->name = json_encode($name);
            }

            // Update type if provided
            if ($request->has('tipo_establecimiento') || $request->has('nivel_atencion')) {
                $type = json_decode($facility->type, true) ?? [[]];
                if ($request->has('tipo_establecimiento')) {
                    $type[0]['coding'][0]['display'] = $request->tipo_establecimiento;
                }
                if ($request->has('nivel_atencion')) {
                    $type[0]['coding'][0]['code'] = (string)$request->nivel_atencion;
                }
                $facility->type = json_encode($type);
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
                $facility->telecom = json_encode($telecom);
            }

            // Update alias (SISA code) if provided
            if ($request->has('codigo_sisa')) {
                $facility->alias = json_encode([$request->codigo_sisa]);
            }

            // Update other fields
            if ($request->has('observaciones')) $facility->description = $request->observaciones;
            if ($request->has('activo')) $facility->status = $request->activo ? 'active' : 'inactive';

            $facility->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Establecimiento actualizado exitosamente',
                'data' => $facility
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating facility: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar establecimiento'
            ], 500);
        }
    }

    /**
     * Remove the specified facility
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $facility = FhirFacility::findOrFail($id);
            $facility->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Establecimiento eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting facility: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar establecimiento'
            ], 500);
        }
    }
}
