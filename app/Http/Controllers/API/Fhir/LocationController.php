<?php

namespace App\Http\Controllers\Api\Fhir;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::with(['address', 'facility', 'encounters', 'juntasMedicas', 'localidad'])->get();
        return response()->json($locations);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fhir_id' => 'required|string|unique:fhir_locations',
            'status' => 'required|string',
            'name' => 'required|array',
            'alias' => 'nullable|array',
            'description' => 'nullable|string',
            'mode' => 'required|string',
            'type' => 'required|array',
            'telecom' => 'nullable|array',
            'fhir_address_id' => 'required|exists:fhir_addresses,fhir_address_id',
            'physicalType' => 'required|array',
            'position' => 'nullable|array',
            'idlocalidad' => 'required|exists:localidades,idlocalidad'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $location = Location::create($request->all());
        return response()->json($location, 201);
    }

    public function show(Location $location)
    {
        return response()->json($location->load(['address', 'facility', 'encounters', 'juntasMedicas', 'localidad']));
    }

    public function update(Request $request, Location $location)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string',
            'name' => 'required|array',
            'alias' => 'nullable|array',
            'description' => 'nullable|string',
            'mode' => 'required|string',
            'type' => 'required|array',
            'telecom' => 'nullable|array',
            'fhir_address_id' => 'required|exists:fhir_addresses,fhir_address_id',
            'physicalType' => 'required|array',
            'position' => 'nullable|array',
            'idlocalidad' => 'required|exists:localidades,idlocalidad'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $location->update($request->all());
        return response()->json($location);
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return response()->json(null, 204);
    }
}
