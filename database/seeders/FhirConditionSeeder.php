<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\FhirConditionStatus;

class FhirConditionSeeder extends Seeder
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

        $conditions = [
            [
                'fhir_id' => Str::uuid()->toString(),
                'clinical_status' => FhirConditionStatus::ACTIVE->value,
                'fhir_patient_id' => $patients[0]->fhir_patient_id,
                'fhir_encounter_id' => $encounters[0]->fhir_encounter_id,
                'recorded_date' => now()->subDays(30),
                'code' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://hl7.org/fhir/sid/icd-10',
                            'code' => 'M54.5',
                            'display' => 'Lumbago no especificado'
                        ]
                    ],
                    'text' => 'Lumbago crónico'
                ]),
                'note' => 'Paciente con historia de dolor lumbar crónico de 2 años de evolución. Refiere empeoramiento en los últimos 3 meses después de levantar objetos pesados en el trabajo.'
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'clinical_status' => FhirConditionStatus::ACTIVE->value,
                'fhir_patient_id' => $patients[1]->fhir_patient_id,
                'fhir_encounter_id' => $encounters[1]->fhir_encounter_id,
                'recorded_date' => now()->subDays(15),
                'code' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://hl7.org/fhir/sid/icd-10',
                            'code' => 'F43.2',
                            'display' => 'Trastorno de adaptación'
                        ]
                    ],
                    'text' => 'Trastorno de adaptación con ansiedad'
                ]),
                'note' => 'Paciente presenta síntomas de ansiedad y dificultad para adaptarse al entorno laboral luego de un cambio de puesto. Refiere insomnio, preocupación excesiva y dificultad para concentrarse.'
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'clinical_status' => FhirConditionStatus::ACTIVE->value,
                'fhir_patient_id' => $patients[2]->fhir_patient_id,
                'fhir_encounter_id' => $encounters[2]->fhir_encounter_id,
                'recorded_date' => now()->subDays(40),
                'code' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://hl7.org/fhir/sid/icd-10',
                            'code' => 'S83.2',
                            'display' => 'Desgarro de menisco, presente'
                        ]
                    ],
                    'text' => 'Lesión meniscal de rodilla derecha'
                ]),
                'note' => 'Paciente sufre lesión en rodilla derecha durante actividad deportiva. Refiere dolor, inflamación y limitación de movimiento. La resonancia magnética confirma desgarro de menisco medial.'
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'clinical_status' => FhirConditionStatus::RESOLVED->value,
                'fhir_patient_id' => $patients[0]->fhir_patient_id,
                'fhir_encounter_id' => $encounters[3]->fhir_encounter_id,
                'recorded_date' => now()->subDays(60),
                'code' => json_encode([
                    'coding' => [
                        [
                            'system' => 'http://hl7.org/fhir/sid/icd-10',
                            'code' => 'J45.9',
                            'display' => 'Asma, no especificada'
                        ]
                    ],
                    'text' => 'Asma bronquial'
                ]),
                'note' => 'Paciente con historia de asma bronquial desde la infancia. Actualmente bien controlado con medicación, sin crisis en los últimos 6 meses. Se considera condición resuelta para fines laborales.'
            ]
        ];

        foreach ($conditions as $condition) {
            DB::table('fhir_conditions')->insert(array_merge($condition, [
                'created_at' => $condition['recorded_date'],
                'updated_at' => now(),
            ]));
        }
    }
}
