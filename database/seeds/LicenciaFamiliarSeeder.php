<?php
use Illuminate\Database\Seeder;

class LicenciaFamiliarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\LicenciaFamiliar::class, 10)->create();
    }
}
