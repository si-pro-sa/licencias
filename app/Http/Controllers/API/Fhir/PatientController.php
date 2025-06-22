<?php

namespace App\Http\Controllers\Api\Fhir;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with(['address', 'agente', 'encounters', 'conditions', 'observations', 'documentReferences'])->get();
        return response()->json($patients);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fhir_id' => 'required|string|unique:fhir_patients',
            'active' => 'required|boolean',
            'name' => 'required|array',
            'gender' => 'required|string',
            'birth_date' => 'required|date',
            'deceased' => 'nullable|date',
            'fhir_address_id' => 'required|exists:fhir_addresses,fhir_address_id',
            'telecom' => 'nullable|array',
            'communication' => 'nullable|array',
            'generalPractitioner' => 'nullable|array',
            'managingOrganization' => 'nullable|array',
            'link' => 'nullable|array',
            'idagente' => 'required|exists:agentes,idagente'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $patient = Patient::create($request->all());
        return response()->json($patient, 201);
    }

    public function show(Patient $patient)
    {
        return response()->json($patient->load(['address', 'agente', 'encounters', 'conditions', 'observations', 'documentReferences']));
    }

    public function update(Request $request, Patient $patient)
    {
        $validator = Validator::make($request->all(), [
            'active' => 'required|boolean',
            'name' => 'required|array',
            'gender' => 'required|string',
            'birth_date' => 'required|date',
            'deceased' => 'nullable|date',
            'fhir_address_id' => 'required|exists:fhir_addresses,fhir_address_id',
            'telecom' => 'nullable|array',
            'communication' => 'nullable|array',
            'generalPractitioner' => 'nullable|array',
            'managingOrganization' => 'nullable|array',
            'link' => 'nullable|array',
            'idagente' => 'required|exists:agentes,idagente'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $patient->update($request->all());
        return response()->json($patient);
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json(null, 204);
    }
}
