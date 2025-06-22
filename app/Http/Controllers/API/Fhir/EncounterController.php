<?php

namespace App\Http\Controllers\Api\Fhir;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Encounter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EncounterController extends Controller
{
    public function index()
    {
        $encounters = Encounter::with([
            'patient',
            'provider',
            'facility',
            'location',
            'licencia',
            'conditions',
            'observations',
            'documentReferences',
            'informes'
        ])->get();
        return response()->json($encounters);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fhir_id' => 'required|string|unique:fhir_encounters',
            'status' => 'required|string',
            'class' => 'required|array',
            'type' => 'required|array',
            'serviceType' => 'required|array',
            'subject' => 'required|array',
            'participant' => 'required|array',
            'period' => 'required|array',
            'length' => 'nullable|array',
            'reasonCode' => 'nullable|array',
            'diagnosis' => 'nullable|array',
            'account' => 'nullable|array',
            'hospitalization' => 'nullable|array',
            'location' => 'required|array',
            'fhir_patient_id' => 'required|exists:fhir_patients,fhir_patient_id',
            'fhir_provider_id' => 'required|exists:fhir_providers,fhir_provider_id',
            'fhir_facility_id' => 'required|exists:fhir_facilities,fhir_facility_id',
            'fhir_location_id' => 'required|exists:fhir_locations,fhir_location_id',
            'idlicencia' => 'required|exists:licencias,idlicencia'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $encounter = Encounter::create($request->all());
        return response()->json($encounter, 201);
    }

    public function show(Encounter $encounter)
    {
        return response()->json($encounter->load([
            'patient',
            'provider',
            'facility',
            'location',
            'licencia',
            'conditions',
            'observations',
            'documentReferences',
            'informes'
        ]));
    }

    public function update(Request $request, Encounter $encounter)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string',
            'class' => 'required|array',
            'type' => 'required|array',
            'serviceType' => 'required|array',
            'subject' => 'required|array',
            'participant' => 'required|array',
            'period' => 'required|array',
            'length' => 'nullable|array',
            'reasonCode' => 'nullable|array',
            'diagnosis' => 'nullable|array',
            'account' => 'nullable|array',
            'hospitalization' => 'nullable|array',
            'location' => 'required|array',
            'fhir_patient_id' => 'required|exists:fhir_patients,fhir_patient_id',
            'fhir_provider_id' => 'required|exists:fhir_providers,fhir_provider_id',
            'fhir_facility_id' => 'required|exists:fhir_facilities,fhir_facility_id',
            'fhir_location_id' => 'required|exists:fhir_locations,fhir_location_id',
            'idlicencia' => 'required|exists:licencias,idlicencia'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $encounter->update($request->all());
        return response()->json($encounter);
    }

    public function destroy(Encounter $encounter)
    {
        $encounter->delete();
        return response()->json(null, 204);
    }
}
