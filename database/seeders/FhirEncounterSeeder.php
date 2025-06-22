<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\FhirEncounterStatus;

class FhirEncounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure patients, providers, facilities and locations exist
        $patients = DB::table('fhir_patients')->get();
        $providers = DB::table('fhir_providers')->get();
        $facilities = DB::table('fhir_facilities')->get();
        $locations = DB::table('fhir_locations')->get();

        if ($patients->isEmpty() || $providers->isEmpty() || $facilities->isEmpty() || $locations->isEmpty()) {
            $this->command->error('Please run required seeders first (FhirPatientSeeder, FhirProviderSeeder, FhirFacilitySeeder, FhirLocationSeeder)');
            return;
        }

        // Get juntas_medicas if they exist
        $juntasMedicas = DB::table('juntas_medicas')->get();

        // Create encounters
        $encounters = [
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirEncounterStatus::FINISHED->value,
                'class' => 'AMB', // Ambulatory
                'fhir_patient_id' => $patients[0]->fhir_patient_id,
                'fhir_provider_id' => $providers[0]->fhir_provider_id,
                'fhir_facility_id' => $facilities[0]->fhir_facility_id,
                'fhir_location_id' => $locations[0]->fhir_location_id,
                'start' => now()->subDays(30),
                'end' => now()->subDays(30)->addHours(1),
                'reason' => 'Consulta inicial para evaluación de aptitud laboral',
                'diagnosis' => json_encode([
                    [
                        'condition' => [
                            'display' => 'Lumbago crónico'
                        ],
                        'rank' => 1
                    ]
                ]),
                'idjuntamedica' => $juntasMedicas->isNotEmpty() ? $juntasMedicas->first()->idjuntamedica : null
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirEncounterStatus::FINISHED->value,
                'class' => 'AMB', // Ambulatory
                'fhir_patient_id' => $patients[1]->fhir_patient_id,
                'fhir_provider_id' => $providers[1]->fhir_provider_id,
                'fhir_facility_id' => $facilities[1]->fhir_facility_id,
                'fhir_location_id' => $locations[1]->fhir_location_id,
                'start' => now()->subDays(15),
                'end' => now()->subDays(15)->addHours(1),
                'reason' => 'Evaluación psiquiátrica por situación de estrés laboral',
                'diagnosis' => json_encode([
                    [
                        'condition' => [
                            'display' => 'Trastorno de adaptación con ansiedad'
                        ],
                        'rank' => 1
                    ]
                ]),
                'idjuntamedica' => $juntasMedicas->count() > 1 ? $juntasMedicas[1]->idjuntamedica : null
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirEncounterStatus::PLANNED->value,
                'class' => 'AMB', // Ambulatory
                'fhir_patient_id' => $patients[2]->fhir_patient_id,
                'fhir_provider_id' => $providers[2]->fhir_provider_id,
                'fhir_facility_id' => $facilities[0]->fhir_facility_id,
                'fhir_location_id' => $locations[0]->fhir_location_id,
                'start' => now()->addDays(5),
                'end' => null,
                'reason' => 'Control por lesión en rodilla derecha',
                'diagnosis' => null,
                'idjuntamedica' => null
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'status' => FhirEncounterStatus::ARRIVED->value,
                'class' => 'AMB', // Ambulatory
                'fhir_patient_id' => $patients[0]->fhir_patient_id,
                'fhir_provider_id' => $providers[2]->fhir_provider_id,
                'fhir_facility_id' => $facilities[0]->fhir_facility_id,
                'fhir_location_id' => $locations[0]->fhir_location_id,
                'start' => now(),
                'end' => null,
                'reason' => 'Seguimiento de tratamiento por lumbalgia',
                'diagnosis' => null,
                'idjuntamedica' => null
            ],
        ];

        foreach ($encounters as $encounter) {
            DB::table('fhir_encounters')->insert(array_merge($encounter, [
                'created_at' => $encounter['start'],
                'updated_at' => now(),
            ]));
        }
    }
}
