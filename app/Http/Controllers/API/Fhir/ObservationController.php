<?php

namespace App\Http\Controllers\Api\Fhir;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Observation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ObservationController extends Controller
{
    public function index()
    {
        $observations = Observation::with(['patient', 'encounter', 'provider'])->get();
        return response()->json($observations);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fhir_id' => 'required|string|unique:fhir_observations',
            'status' => 'required|array',
            'category' => 'required|array',
            'code' => 'required|array',
            'subject' => 'required|array',
            'focus' => 'nullable|array',
            'encounter' => 'required|array',
            'effective' => 'required|array',
            'issued' => 'required|date',
            'performer' => 'required|array',
            'value' => 'required|array',
            'dataAbsentReason' => 'nullable|array',
            'interpretation' => 'nullable|array',
            'note' => 'nullable|array',
            'bodySite' => 'nullable|array',
            'method' => 'nullable|array',
            'specimen' => 'nullable|array',
            'device' => 'nullable|array',
            'referenceRange' => 'nullable|array',
            'hasMember' => 'nullable|array',
            'derivedFrom' => 'nullable|array',
            'component' => 'nullable|array',
            'fhir_patient_id' => 'required|exists:fhir_patients,fhir_patient_id',
            'fhir_encounter_id' => 'required|exists:fhir_encounters,fhir_encounter_id',
            'fhir_provider_id' => 'required|exists:fhir_providers,fhir_provider_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $observation = Observation::create($request->all());
        return response()->json($observation, 201);
    }

    public function show(Observation $observation)
    {
        return response()->json($observation->load(['patient', 'encounter', 'provider']));
    }

    public function update(Request $request, Observation $observation)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|array',
            'category' => 'required|array',
            'code' => 'required|array',
            'subject' => 'required|array',
            'focus' => 'nullable|array',
            'encounter' => 'required|array',
            'effective' => 'required|array',
            'issued' => 'required|date',
            'performer' => 'required|array',
            'value' => 'required|array',
            'dataAbsentReason' => 'nullable|array',
            'interpretation' => 'nullable|array',
            'note' => 'nullable|array',
            'bodySite' => 'nullable|array',
            'method' => 'nullable|array',
            'specimen' => 'nullable|array',
            'device' => 'nullable|array',
            'referenceRange' => 'nullable|array',
            'hasMember' => 'nullable|array',
            'derivedFrom' => 'nullable|array',
            'component' => 'nullable|array',
            'fhir_patient_id' => 'required|exists:fhir_patients,fhir_patient_id',
            'fhir_encounter_id' => 'required|exists:fhir_encounters,fhir_encounter_id',
            'fhir_provider_id' => 'required|exists:fhir_providers,fhir_provider_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $observation->update($request->all());
        return response()->json($observation);
    }

    public function destroy(Observation $observation)
    {
        $observation->delete();
        return response()->json(null, 204);
    }
}
