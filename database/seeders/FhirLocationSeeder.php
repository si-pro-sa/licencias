<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\FhirResourceStatus;

class FhirLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirResourceStatus::ACTIVE->value,
                'name' => json_encode([
                    'text' => 'Hospital Central',
                    'primary' => true
                ]),
                'description' => 'Hospital Central de la Ciudad',
                'mode' => 'instance',
                'type' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/v3-RoleCode',
                            'code' => 'HOSP',
                            'display' => 'Hospital'
                        ]
                    ],
                    'text' => 'Hospital'
                ]),
                'telecom' => json_encode([
                    [
                        'system' => 'phone',
                        'value' => '+54 11 4567-8900',
                        'use' => 'work'
                    ],
                    [
                        'system' => 'email',
                        'value' => 'info@hospitalcentral.gov.ar',
                        'use' => 'work'
                    ]
                ]),
                'fhir_address_id' => 1,
                'physicalType' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/location-physical-type',
                            'code' => 'bu',
                            'display' => 'Building'
                        ]
                    ],
                    'text' => 'Building'
                ]),
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirResourceStatus::ACTIVE->value,
                'name' => json_encode([
                    'text' => 'Clínica San Martín',
                    'primary' => true
                ]),
                'description' => 'Clínica Privada San Martín',
                'mode' => 'instance',
                'type' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/v3-RoleCode',
                            'code' => 'HOSP',
                            'display' => 'Hospital'
                        ]
                    ],
                    'text' => 'Clínica'
                ]),
                'telecom' => json_encode([
                    [
                        'system' => 'phone',
                        'value' => '+54 11 2345-6789',
                        'use' => 'work'
                    ]
                ]),
                'fhir_address_id' => 2,
                'physicalType' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://terminology.hl7.org/CodeSystem/location-physical-type',
                            'code' => 'bu',
                            'display' => 'Building'
                        ]
                    ],
                    'text' => 'Building'
                ]),
            ],
        ];

        foreach ($locations as $location) {
            DB::table('fhir_locations')->insert(array_merge($location, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
