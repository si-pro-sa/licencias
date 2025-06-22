<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FhirProvider;
use App\Models\FhirFacility;
use App\Models\FhirAddress;
use Illuminate\Support\Str;

class FhirGestionClinicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample addresses
        $address1 = FhirAddress::create([
            'fhir_id' => Str::uuid(),
            'use' => 'work',
            'type' => 'physical',
            'line1' => 'Av. Corrientes 1234',
            'city' => 'Buenos Aires',
            'state' => 'CABA',
            'postal_code' => '1043',
            'country' => 'AR'
        ]);

        $address2 = FhirAddress::create([
            'fhir_id' => Str::uuid(),
            'use' => 'work',
            'type' => 'physical',
            'line1' => 'Av. Rivadavia 5678',
            'city' => 'Buenos Aires',
            'state' => 'CABA',
            'postal_code' => '1406',
            'country' => 'AR'
        ]);

        // Create sample facilities
        $hospital = FhirFacility::create([
            'fhir_id' => Str::uuid(),
            'status' => 'active',
            'name' => json_encode([
                'text' => 'Hospital General de Agudos',
                'use' => 'official'
            ]),
            'alias' => json_encode(['HGA-001']),
            'description' => 'Hospital público de alta complejidad',
            'type' => json_encode([[
                'coding' => [[
                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-RoleCode',
                    'code' => '3',
                    'display' => 'Hospital'
                ]]
            ]]),
            'telecom' => json_encode([
                [
                    'system' => 'phone',
                    'value' => '+54-11-4123-4567',
                    'use' => 'work'
                ],
                [
                    'system' => 'email',
                    'value' => 'contacto@hospital.gov.ar',
                    'use' => 'work'
                ]
            ]),
            'fhir_address_id' => $address1->fhir_address_id
        ]);

        $centroSalud = FhirFacility::create([
            'fhir_id' => Str::uuid(),
            'status' => 'active',
            'name' => json_encode([
                'text' => 'Centro de Salud Barrio Norte',
                'use' => 'official'
            ]),
            'alias' => json_encode(['CS-BN-002']),
            'description' => 'Centro de atención primaria de la salud',
            'type' => json_encode([[
                'coding' => [[
                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-RoleCode',
                    'code' => '1',
                    'display' => 'Centro de Salud'
                ]]
            ]]),
            'telecom' => json_encode([
                [
                    'system' => 'phone',
                    'value' => '+54-11-4987-6543',
                    'use' => 'work'
                ]
            ]),
            'fhir_address_id' => $address2->fhir_address_id
        ]);

        // Create sample providers (internal agents)
        FhirProvider::create([
            'fhir_id' => Str::uuid(),
            'active' => true,
            'name' => json_encode([
                'use' => 'official',
                'family' => 'Pérez',
                'given' => ['Juan'],
                'identifier' => '12345678'
            ]),
            'gender' => 'male',
            'birth_date' => '1980-05-15',
            'qualification' => json_encode([[
                'code' => [
                    'coding' => [[
                        'system' => 'http://terminology.hl7.org/CodeSystem/practitioner-role',
                        'code' => 'medico-general',
                        'display' => 'Médico/a General'
                    ]]
                ],
                'identifier' => [
                    ['system' => 'matricula', 'value' => 'MN-12345']
                ]
            ]]),
            'telecom' => json_encode([
                [
                    'system' => 'phone',
                    'value' => '+54-11-1234-5678',
                    'use' => 'work'
                ],
                [
                    'system' => 'email',
                    'value' => 'juan.perez@hospital.gov.ar',
                    'use' => 'work'
                ]
            ]),
            'fhir_facility_id' => $hospital->fhir_facility_id,
            'idagente' => 1 // Referencia al agente creado en la migración
        ]);

        FhirProvider::create([
            'fhir_id' => Str::uuid(),
            'active' => true,
            'name' => json_encode([
                'use' => 'official',
                'family' => 'González',
                'given' => ['María'],
                'identifier' => '87654321'
            ]),
            'gender' => 'female',
            'birth_date' => '1975-08-22',
            'qualification' => json_encode([[
                'code' => [
                    'coding' => [[
                        'system' => 'http://terminology.hl7.org/CodeSystem/practitioner-role',
                        'code' => 'enfermero',
                        'display' => 'Enfermero/a'
                    ]]
                ],
                'identifier' => [
                    ['system' => 'matricula', 'value' => 'ENF-98765']
                ]
            ]]),
            'telecom' => json_encode([
                [
                    'system' => 'phone',
                    'value' => '+54-11-9876-5432',
                    'use' => 'work'
                ],
                [
                    'system' => 'email',
                    'value' => 'maria.gonzalez@centrosalud.gov.ar',
                    'use' => 'work'
                ]
            ]),
            'fhir_facility_id' => $centroSalud->fhir_facility_id,
            'idagente' => 2 // Referencia al agente creado en la migración
        ]);

        // Create sample external provider
        FhirProvider::create([
            'fhir_id' => Str::uuid(),
            'active' => true,
            'name' => json_encode([
                'use' => 'official',
                'family' => 'López',
                'given' => ['Ana'],
                'identifier' => '34567890'
            ]),
            'gender' => 'female',
            'birth_date' => '1985-03-10',
            'qualification' => json_encode([[
                'code' => [
                    'coding' => [[
                        'system' => 'http://terminology.hl7.org/CodeSystem/practitioner-role',
                        'code' => 'psicologo',
                        'display' => 'Psicólogo/a'
                    ]]
                ],
                'identifier' => [
                    ['system' => 'matricula', 'value' => 'PSI-34567']
                ]
            ]]),
            'telecom' => json_encode([
                [
                    'system' => 'phone',
                    'value' => '+54-11-3456-7890',
                    'use' => 'work'
                ],
                [
                    'system' => 'email',
                    'value' => 'ana.lopez@consultorio.com',
                    'use' => 'work'
                ]
            ]),
            'idagente' => null // Prestador externo, no vinculado a agente
        ]);
    }
}
