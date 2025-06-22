<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Enums\FhirAddressType;
use App\Enums\FhirAddressUse;

class FhirAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $addresses = [
            [
                'fhir_id' => Str::uuid()->toString(),
                'use' => FhirAddressUse::HOME->value,
                'type' => FhirAddressType::PHYSICAL->value,
                'line1' => 'Av. Corrientes 1234',
                'line2' => 'Piso 4, Oficina B',
                'city' => 'Buenos Aires',
                'district' => 'CABA',
                'state' => 'Buenos Aires',
                'postal_code' => 'C1043AAZ',
                'country' => 'AR',
                'latitude' => -34.6037,
                'longitude' => -58.3816,
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'use' => FhirAddressUse::WORK->value,
                'type' => FhirAddressType::BOTH->value,
                'line1' => 'Av. 9 de Julio 1000',
                'line2' => null,
                'city' => 'Buenos Aires',
                'district' => 'CABA',
                'state' => 'Buenos Aires',
                'postal_code' => 'C1043AAZ',
                'country' => 'AR',
                'latitude' => -34.6077,
                'longitude' => -58.3800,
            ],
            [
                'fhir_id' => Str::uuid()->toString(),
                'use' => FhirAddressUse::HOME->value,
                'type' => FhirAddressType::PHYSICAL->value,
                'line1' => 'Córdoba 1400',
                'line2' => null,
                'city' => 'Rosario',
                'district' => null,
                'state' => 'Santa Fe',
                'postal_code' => '2000',
                'country' => 'AR',
                'latitude' => -32.9442,
                'longitude' => -60.6505,
            ],
        ];

        foreach ($addresses as $address) {
            DB::table('fhir_addresses')->insert(array_merge($address, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
