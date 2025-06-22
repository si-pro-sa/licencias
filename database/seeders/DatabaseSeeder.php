<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Seeders estándar de la aplicación
        // $this->call(UserSeeder::class);

        // FHIR Seeders - Order is important due to dependencies
        $this->call([
            // Base resources with no dependencies
            FhirAddressSeeder::class,
            FhirLocationSeeder::class,

            // First level dependent resources
            FhirFacilitySeeder::class,

            // Second level dependent resources
            FhirProviderSeeder::class,
            FhirPatientSeeder::class,

            // Third level dependent resources
            FhirEncounterSeeder::class,

            // Fourth level dependent resources
            FhirConditionSeeder::class,
            FhirObservationSeeder::class,
            FhirDocumentReferenceSeeder::class,
            FhirClinicalNoteSeeder::class,
        ]);
    }
}
