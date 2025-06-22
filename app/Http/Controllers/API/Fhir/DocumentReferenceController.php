<?php

namespace App\Http\Controllers\Api\Fhir;

use App\Http\Controllers\Controller;
use App\Models\Fhir\DocumentReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentReferenceController extends Controller
{
    public function index()
    {
        $documentReferences = DocumentReference::with(['patient', 'encounter', 'provider', 'informes'])->get();
        return response()->json($documentReferences);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fhir_id' => 'required|string|unique:fhir_document_references',
            'status' => 'required|array',
            'docStatus' => 'required|array',
            'type' => 'required|array',
            'category' => 'required|array',
            'subject' => 'required|array',
            'encounter' => 'required|array',
            'event' => 'nullable|array',
            'facilityType' => 'nullable|array',
            'practiceSetting' => 'nullable|array',
            'period' => 'nullable|array',
            'date' => 'required|date',
            'author' => 'required|array',
            'attester' => 'nullable|array',
            'custodian' => 'nullable|array',
            'relatesTo' => 'nullable|array',
            'description' => 'nullable|array',
            'securityLabel' => 'nullable|array',
            'content' => 'required|array',
            'context' => 'nullable|array',
            'fhir_patient_id' => 'required|exists:fhir_patients,fhir_patient_id',
            'fhir_encounter_id' => 'required|exists:fhir_encounters,fhir_encounter_id',
            'fhir_provider_id' => 'required|exists:fhir_providers,fhir_provider_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $documentReference = DocumentReference::create($request->all());
        return response()->json($documentReference, 201);
    }

    public function show(DocumentReference $documentReference)
    {
        return response()->json($documentReference->load(['patient', 'encounter', 'provider', 'informes']));
    }

    public function update(Request $request, DocumentReference $documentReference)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|array',
            'docStatus' => 'required|array',
            'type' => 'required|array',
            'category' => 'required|array',
            'subject' => 'required|array',
            'encounter' => 'required|array',
            'event' => 'nullable|array',
            'facilityType' => 'nullable|array',
            'practiceSetting' => 'nullable|array',
            'period' => 'nullable|array',
            'date' => 'required|date',
            'author' => 'required|array',
            'attester' => 'nullable|array',
            'custodian' => 'nullable|array',
            'relatesTo' => 'nullable|array',
            'description' => 'nullable|array',
            'securityLabel' => 'nullable|array',
            'content' => 'required|array',
            'context' => 'nullable|array',
            'fhir_patient_id' => 'required|exists:fhir_patients,fhir_patient_id',
            'fhir_encounter_id' => 'required|exists:fhir_encounters,fhir_encounter_id',
            'fhir_provider_id' => 'required|exists:fhir_providers,fhir_provider_id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $documentReference->update($request->all());
        return response()->json($documentReference);
    }

    public function destroy(DocumentReference $documentReference)
    {
        $documentReference->delete();
        return response()->json(null, 204);
    }
}
