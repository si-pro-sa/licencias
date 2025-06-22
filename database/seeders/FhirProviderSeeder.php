<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\FhirGender;

class FhirProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure facilities and addresses exist
        $facility1 = DB::table('fhir_facilities')->first();
        $facility2 = DB::table('fhir_facilities')->skip(1)->first();

        if (!$facility1 || !$facility2) {
            $this->command->error('Please run FhirFacilitySeeder first');
            return;
        }

        // Get existing agentes if available
        $agente1 = DB::table('agentes')->where('idagente', 1)->first();
        $agente2 = DB::table('agentes')->where('idagente', 2)->first();
        $agente3 = DB::table('agentes')->where('idagente', 3)->first();

        $providers = [
            [
                'fhir_id' => Str::uuid()->toString(),
                'active' => true,
                'name' => json_encode([
                    'text' => 'Dr. Juan Pérez',
                    'family' => 'Pérez',
                    'given' => ['Juan'],
                    'prefix' => ['Dr.'],
                ]),
                'gender' => FhirGender::MALE->value,
                'birth_date' => '1975-06-15',
                'qualification' => json_encode([
                    [
                        'code' => [
                            'coding' => [
                                [
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v2-0360',
                                    'code' => 'MD',
                                    'display' => 'Doctor of Medicine'
                                ]
                            ],
                            'text' => 'Médico Especialista en Medicina Laboral'
                        ],
                        'period' => [
                            'start' => '2000-01-15'
                        ]
                    ]
                ]),
                'telecom' => json_encode([
                    [
                        'system' => 'phone',
                        'value' => '+54 11 4567-8999',
                        'use' => 'work'
                    ],
                    [
                        'system' => 'email',
                        'value' => 'juan.perez@hospitalcentral.gov.ar',
                        'use' => 'work'
                    ]
                ]),
                'fhir_address_id' => 1,
                'fhir_facility_id' => $facility1->fhir_facility_id,
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
                'idagente' => $agente1 ? $agente1->idagente : null,
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'active' => true,
                'name' => json_encode([
                    'text' => 'Dra. María González',
                    'family' => 'González',
                    'given' => ['María'],
                    'prefix' => ['Dra.'],
                ]),
                'gender' => FhirGender::FEMALE->value,
                'birth_date' => '1980-03-22',
                'qualification' => json_encode([
                    [
                        'code' => [
                            'coding' => [
                                [
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v2-0360',
                                    'code' => 'MD',
                                    'display' => 'Doctor of Medicine'
                                ]
                            ],
                            'text' => 'Médica Especialista en Psiquiatría'
                        ],
                        'period' => [
                            'start' => '2005-06-10'
                        ]
                    ]
                ]),
                'telecom' => json_encode([
                    [
                        'system' => 'phone',
                        'value' => '+54 11 2345-6799',
                        'use' => 'work'
                    ],
                    [
                        'system' => 'email',
                        'value' => 'maria.gonzalez@clinicasanmartin.com.ar',
                        'use' => 'work'
                    ]
                ]),
                'fhir_address_id' => 2,
                'fhir_facility_id' => $facility2->fhir_facility_id,
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
                'idagente' => $agente2 ? $agente2->idagente : null,
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'active' => true,
                'name' => json_encode([
                    'text' => 'Dr. Carlos Rodríguez',
                    'family' => 'Rodríguez',
                    'given' => ['Carlos'],
                    'prefix' => ['Dr.'],
                ]),
                'gender' => FhirGender::MALE->value,
                'birth_date' => '1972-11-08',
                'qualification' => json_encode([
                    [
                        'code' => [
                            'coding' => [
                                [
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v2-0360',
                                    'code' => 'MD',
                                    'display' => 'Doctor of Medicine'
                                ]
                            ],
                            'text' => 'Médico Especialista en Traumatología'
                        ],
                        'period' => [
                            'start' => '1998-09-20'
                        ]
                    ]
                ]),
                'telecom' => json_encode([
                    [
                        'system' => 'phone',
                        'value' => '+54 11 4567-8901',
                        'use' => 'work'
                    ],
                    [
                        'system' => 'email',
                        'value' => 'carlos.rodriguez@hospitalcentral.gov.ar',
                        'use' => 'work'
                    ]
                ]),
                'fhir_address_id' => 1,
                'fhir_facility_id' => $facility1->fhir_facility_id,
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
                'idagente' => $agente3 ? $agente3->idagente : null,
            ],
        ];

        foreach ($providers as $provider) {
            DB::table('fhir_providers')->insert(array_merge($provider, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
