<?php
use Illuminate\Database\Seeder;

class TipoLicenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(App\Models\TipoLicencia::class, 50)->create()->each(function ($tipoLicencia) {
            $tipoLicencia->licencias()->save(factory(App\Models\Licencia::class)->make());
        });

    }
}
