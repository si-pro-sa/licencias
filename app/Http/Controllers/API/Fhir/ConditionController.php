<?php

namespace App\Http\Controllers\Api\Fhir;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Condition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConditionController extends Controller
{
    public function index()
    {
        $conditions = Condition::with(['patient', 'encounter', 'provider'])->get();
        return response()->json($conditions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fhir_id' => 'required|string|unique:fhir_conditions',
            'clinicalStatus' => 'required|array',
            'verificationStatus' => 'required|array',
            'category' => 'required|array',
            'severity' => 'required|array',
            'code' => 'required|array',
            'bodySite' => 'nullable|array',
            'subject' => 'required|array',
            'encounter' => 'required|array',
            'onset' => 'required|array',
            'abatement' => 'nullable|array',
            'recordedDate' => 'required|date',
            'recorder' => 'nullable|array',
            'asserter' => 'nullable|array',
            'stage' => 'nullable|array',
            'evidence' => 'nullable|array',
            'note' => 'nullable|array',
            'fhir_patient_id' => 'required|exists:fhir_patients,fhir_patient_id',
            'fhir_encounter_id' => 'required|exists:fhir_encounters,fhir_encounter_id',
            'fhir_provider_id' => 'required|exists:fhir_providers,fhir_provider_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $condition = Condition::create($request->all());
        return response()->json($condition, 201);
    }

    public function show(Condition $condition)
    {
        return response()->json($condition->load(['patient', 'encounter', 'provider']));
    }

    public function update(Request $request, Condition $condition)
    {
        $validator = Validator::make($request->all(), [
            'clinicalStatus' => 'required|array',
            'verificationStatus' => 'required|array',
            'category' => 'required|array',
            'severity' => 'required|array',
            'code' => 'required|array',
            'bodySite' => 'nullable|array',
            'subject' => 'required|array',
            'encounter' => 'required|array',
            'onset' => 'required|array',
            'abatement' => 'nullable|array',
            'recordedDate' => 'required|date',
            'recorder' => 'nullable|array',
            'asserter' => 'nullable|array',
            'stage' => 'nullable|array',
            'evidence' => 'nullable|array',
            'note' => 'nullable|array',
            'fhir_patient_id' => 'required|exists:fhir_patients,fhir_patient_id',
            'fhir_encounter_id' => 'required|exists:fhir_encounters,fhir_encounter_id',
            'fhir_provider_id' => 'required|exists:fhir_providers,fhir_provider_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $condition->update($request->all());
        return response()->json($condition);
    }

    public function destroy(Condition $condition)
    {
        $condition->delete();
        return response()->json(null, 204);
    }
}
