<?php

namespace App\Http\Controllers\Api\Fhir;

use App\Http\Controllers\Controller;
use App\Models\Fhir\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::with(['location', 'facility', 'provider', 'patient'])->get();
        return response()->json($addresses);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fhir_id' => 'required|string|unique:fhir_addresses',
            'use' => 'required|string',
            'type' => 'required|string',
            'line1' => 'required|string',
            'line2' => 'nullable|string',
            'city' => 'required|string',
            'district' => 'nullable|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $address = Address::create($request->all());
        return response()->json($address, 201);
    }

    public function show(Address $address)
    {
        return response()->json($address->load(['location', 'facility', 'provider', 'patient']));
    }

    public function update(Request $request, Address $address)
    {
        $validator = Validator::make($request->all(), [
            'use' => 'required|string',
            'type' => 'required|string',
            'line1' => 'required|string',
            'line2' => 'nullable|string',
            'city' => 'required|string',
            'district' => 'nullable|string',
            'state' => 'required|string',
            'postal_code' => 'required|string',
            'country' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $address->update($request->all());
        return response()->json($address);
    }

    public function destroy(Address $address)
    {
        $address->delete();
        return response()->json(null, 204);
    }
}
