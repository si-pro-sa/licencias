<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\FhirGender;

class FhirPatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure addresses exist
        $address1 = DB::table('fhir_addresses')->first();
        $address2 = DB::table('fhir_addresses')->skip(1)->first();
        $address3 = DB::table('fhir_addresses')->skip(2)->first();

        if (!$address1 || !$address2 || !$address3) {
            $this->command->error('Please run FhirAddressSeeder first');
            return;
        }

        // Ensure providers exist to reference as general practitioners
        $providers = DB::table('fhir_providers')->get();
        if ($providers->isEmpty()) {
            $this->command->error('Please run FhirProviderSeeder first');
            return;
        }

        // Get existing agentes to create different patients
        // We'll create 3 new patients that are not existing agents
        $patients = [
            [
                'fhir_id' => Str::uuid()->toString(),
                'active' => true,
                'name' => json_encode([
                    'text' => 'Roberto Martínez',
                    'family' => 'Martínez',
                    'given' => ['Roberto'],
                ]),
                'gender' => FhirGender::MALE->value,
                'birth_date' => '1982-04-10',
                'fhir_address_id' => $address3->fhir_address_id,
                'telecom' => json_encode([
                    [
                        'system' => 'phone',
                        'value' => '+54 11 4123-4567',
                        'use' => 'home'
                    ],
                    [
                        'system' => 'email',
                        'value' => 'roberto.martinez@example.com',
                        'use' => 'home'
                    ]
                ]),
                'communication' => json_encode([
                    [
                        'language' => [
                            'coding' => [
                                [
                                    'system' => 'urn:ietf:bcp:47',
                                    'code' => 'es-AR',
                                    'display' => 'Spanish (Argentina)'
                                ]
                            ],
                            'text' => 'Español'
                        ],
                        'preferred' => true
                    ]
                ]),
                'generalPractitioner' => json_encode([
                    [
                        'reference' => 'Provider/' . $providers->first()->fhir_id,
                        'display' => json_decode($providers->first()->name)->text
                    ]
                ]),
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'active' => true,
                'name' => json_encode([
                    'text' => 'Laura Sánchez',
                    'family' => 'Sánchez',
                    'given' => ['Laura'],
                ]),
                'gender' => FhirGender::FEMALE->value,
                'birth_date' => '1990-07-22',
                'fhir_address_id' => $address1->fhir_address_id,
                'telecom' => json_encode([
                    [
                        'system' => 'phone',
                        'value' => '+54 11 5678-1234',
                        'use' => 'home'
                    ],
                    [
                        'system' => 'email',
                        'value' => 'laura.sanchez@example.com',
                        'use' => 'home'
                    ]
                ]),
                'communication' => json_encode([
                    [
                        'language' => [
                            'coding' => [
                                [
                                    'system' => 'urn:ietf:bcp:47',
                                    'code' => 'es-AR',
                                    'display' => 'Spanish (Argentina)'
                                ]
                            ],
                            'text' => 'Español'
                        ],
                        'preferred' => true
                    ]
                ]),
                'generalPractitioner' => json_encode([
                    [
                        'reference' => 'Provider/' . $providers[1]->fhir_id,
                        'display' => json_decode($providers[1]->name)->text
                    ]
                ]),
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'active' => true,
                'name' => json_encode([
                    'text' => 'Pedro Gómez',
                    'family' => 'Gómez',
                    'given' => ['Pedro'],
                ]),
                'gender' => FhirGender::MALE->value,
                'birth_date' => '1965-12-15',
                'fhir_address_id' => $address2->fhir_address_id,
                'telecom' => json_encode([
                    [
                        'system' => 'phone',
                        'value' => '+54 11 8765-4321',
                        'use' => 'home'
                    ],
                    [
                        'system' => 'email',
                        'value' => 'pedro.gomez@example.com',
                        'use' => 'home'
                    ]
                ]),
                'communication' => json_encode([
                    [
                        'language' => [
                            'coding' => [
                                [
                                    'system' => 'urn:ietf:bcp:47',
                                    'code' => 'es-AR',
                                    'display' => 'Spanish (Argentina)'
                                ]
                            ],
                            'text' => 'Español'
                        ],
                        'preferred' => true
                    ]
                ]),
                'generalPractitioner' => json_encode([
                    [
                        'reference' => 'Provider/' . $providers[2]->fhir_id,
                        'display' => json_decode($providers[2]->name)->text
                    ]
                ]),
            ],
        ];

        foreach ($patients as $patient) {
            DB::table('fhir_patients')->insert(array_merge($patient, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
