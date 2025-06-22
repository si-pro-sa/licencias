<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\FhirObservationStatus;

class FhirObservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure patients and encounters exist
        $patients = DB::table('fhir_patients')->get();
        $encounters = DB::table('fhir_encounters')->get();

        if ($patients->isEmpty() || $encounters->isEmpty()) {
            $this->command->error('Please run required seeders first (FhirPatientSeeder, FhirEncounterSeeder)');
            return;
        }

        $observations = [
            // Signos vitales para el primer paciente
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirObservationStatus::FINAL->value,
                'category' => json_encode([
                    [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                                'code' => 'vital-signs',
                                'display' => 'Vital Signs'
                            ]
                        ],
                        'text' => 'Signos Vitales'
                    ]
                ]),
                'code' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://loinc.org',
                            'code' => '8480-6',
                            'display' => 'Systolic blood pressure'
                        ]
                    ],
                    'text' => 'Presión arterial sistólica'
                ]),
                'fhir_patient_id' => $patients[0]->fhir_patient_id,
                'fhir_encounter_id' => $encounters[0]->fhir_encounter_id,
                'effective_datetime' => now()->subDays(30),
                'issued' => now()->subDays(30),
                'value' => json_encode([
                    'valueQuantity' => [
                        'value' => 135,
                        'unit' => 'mmHg',
                        'system' => 'http://unitsofmeasure.org',
                        'code' => 'mm[Hg]'
                    ]
                ]),
                'data_type' => 'Quantity',
                'note' => 'Presión arterial ligeramente elevada. Se recomienda seguimiento.'
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirObservationStatus::FINAL->value,
                'category' => json_encode([
                    [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                                'code' => 'vital-signs',
                                'display' => 'Vital Signs'
                            ]
                        ],
                        'text' => 'Signos Vitales'
                    ]
                ]),
                'code' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://loinc.org',
                            'code' => '8462-4',
                            'display' => 'Diastolic blood pressure'
                        ]
                    ],
                    'text' => 'Presión arterial diastólica'
                ]),
                'fhir_patient_id' => $patients[0]->fhir_patient_id,
                'fhir_encounter_id' => $encounters[0]->fhir_encounter_id,
                'effective_datetime' => now()->subDays(30),
                'issued' => now()->subDays(30),
                'value' => json_encode([
                    'valueQuantity' => [
                        'value' => 85,
                        'unit' => 'mmHg',
                        'system' => 'http://unitsofmeasure.org',
                        'code' => 'mm[Hg]'
                    ]
                ]),
                'data_type' => 'Quantity',
                'note' => null
            ],
            // Evaluación psicológica para el segundo paciente
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirObservationStatus::FINAL->value,
                'category' => json_encode([
                    [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                                'code' => 'survey',
                                'display' => 'Survey'
                            ]
                        ],
                        'text' => 'Evaluación'
                    ]
                ]),
                'code' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://loinc.org',
                            'code' => '69725-0',
                            'display' => 'DASS-21 anxiety score'
                        ]
                    ],
                    'text' => 'Escala de ansiedad DASS-21'
                ]),
                'fhir_patient_id' => $patients[1]->fhir_patient_id,
                'fhir_encounter_id' => $encounters[1]->fhir_encounter_id,
                'effective_datetime' => now()->subDays(15),
                'issued' => now()->subDays(15),
                'value' => json_encode([
                    'valueInteger' => 18
                ]),
                'data_type' => 'Integer',
                'note' => 'Puntaje correspondiente a ansiedad moderada-severa. Se recomienda seguimiento psicológico.'
            ],
            // Observación para el tercer paciente
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirObservationStatus::FINAL->value,
                'category' => json_encode([
                    [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                                'code' => 'exam',
                                'display' => 'Exam'
                            ]
                        ],
                        'text' => 'Examen físico'
                    ]
                ]),
                'code' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://loinc.org',
                            'code' => '32411-1',
                            'display' => 'Range of movement assessment'
                        ]
                    ],
                    'text' => 'Rango de movimiento de rodilla'
                ]),
                'fhir_patient_id' => $patients[2]->fhir_patient_id,
                'fhir_encounter_id' => $encounters[2]->fhir_encounter_id,
                'effective_datetime' => now()->subDays(40),
                'issued' => now()->subDays(40),
                'value' => json_encode([
                    'valueString' => 'Flexión limitada a 90 grados. Extensión completa con dolor.'
                ]),
                'data_type' => 'String',
                'note' => 'Se observa inflamación y dolor a la palpación en compartimento medial. Prueba de McMurray positiva.'
            ],
            // Observación de laboratorio para el primer paciente
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirObservationStatus::FINAL->value,
                'category' => json_encode([
                    [
                        'coding' => [
                            [
                                'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                                'code' => 'laboratory',
                                'display' => 'Laboratory'
                            ]
                        ],
                        'text' => 'Laboratorio'
                    ]
                ]),
                'code' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://loinc.org',
                            'code' => '1920-8',
                            'display' => 'Aspartate aminotransferase [Enzymatic activity/volume] in Serum or Plasma'
                        ]
                    ],
                    'text' => 'AST/GOT'
                ]),
                'fhir_patient_id' => $patients[0]->fhir_patient_id,
                'fhir_encounter_id' => $encounters[0]->fhir_encounter_id,
                'effective_datetime' => now()->subDays(28),
                'issued' => now()->subDays(27),
                'value' => json_encode([
                    'valueQuantity' => [
                        'value' => 32,
                        'unit' => 'U/L',
                        'system' => 'http://unitsofmeasure.org',
                        'code' => 'U/L'
                    ]
                ]),
                'data_type' => 'Quantity',
                'note' => 'Valores dentro de rango normal. No se requiere seguimiento adicional.'
            ]
        ];

        foreach ($observations as $observation) {
            DB::table('fhir_observations')->insert(array_merge($observation, [
                'created_at' => $observation['issued'],
                'updated_at' => $observation['issued'],
            ]));
        }
    }
}
