<?php
use Illuminate\Database\Seeder;

class GrupoFamiliarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\GrupoFamiliar::class, 20)->create();

    }
}
