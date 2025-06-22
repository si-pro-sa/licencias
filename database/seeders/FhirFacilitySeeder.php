<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\FhirResourceStatus;

class FhirFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure locations exist
        $location1 = DB::table('fhir_locations')->first();
        $location2 = DB::table('fhir_locations')->skip(1)->first();

        if (!$location1 || !$location2) {
            $this->command->error('Please run FhirLocationSeeder first');
            return;
        }

        $facilities = [
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirResourceStatus::ACTIVE->value,
                'name' => json_encode([
                    'text' => 'Hospital Central - Unidad de Medicina Laboral',
                    'primary' => true
                ]),
                'description' => 'Unidad encargada de las evaluaciones médicas laborales',
                'type' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/organization-type',
                            'code' => 'dept',
                            'display' => 'Hospital Department'
                        ]
                    ],
                    'text' => 'Unidad de Medicina Laboral'
                ]),
                'telecom' => json_encode([
                    [
                        'system' => 'phone',
                        'value' => '+54 11 4567-8910',
                        'use' => 'work'
                    ],
                    [
                        'system' => 'email',
                        'value' => 'medicina.laboral@hospitalcentral.gov.ar',
                        'use' => 'work'
                    ]
                ]),
                'fhir_address_id' => 1,
                'fhir_location_id' => $location1->fhir_location_id,
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirResourceStatus::ACTIVE->value,
                'name' => json_encode([
                    'text' => 'Clínica San Martín - Servicio de Salud Ocupacional',
                    'primary' => true
                ]),
                'description' => 'Servicio especializado en salud ocupacional y medicina del trabajo',
                'type' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/organization-type',
                            'code' => 'dept',
                            'display' => 'Hospital Department'
                        ]
                    ],
                    'text' => 'Servicio de Salud Ocupacional'
                ]),
                'telecom' => json_encode([
                    [
                        'system' => 'phone',
                        'value' => '+54 11 2345-6790',
                        'use' => 'work'
                    ],
                    [
                        'system' => 'email',
                        'value' => 'salud.ocupacional@clinicasanmartin.com.ar',
                        'use' => 'work'
                    ]
                ]),
                'fhir_address_id' => 2,
                'fhir_location_id' => $location2->fhir_location_id,
            ],
        ];

        foreach ($facilities as $facility) {
            DB::table('fhir_facilities')->insert(array_merge($facility, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
