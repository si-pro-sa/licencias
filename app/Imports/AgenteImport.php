<?php

namespace App\Imports;

use App\Models\Agente;
use App\Models\Antiguedad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;


class AgenteImport implements ToCollection, SkipsOnFailure
{

    use SkipsFailures;

    public function collection(Collection $rows)
    {
//        $user = DB::table('sistema.usuario')
//            ->where('nombreusuario', '=', 'migracion')
//            ->first();
        foreach ($rows as $row) {

            $agenteModificar = Agente::where('documento', '=', $row[0])->first();
            if (!empty($agenteModificar)) {
                $anios = $row[1];
                $disponible = 0;
                $agenteModificar->antiguedad = $anios;
                $agenteModificar->save();


                if ($anios >= 15) {
                    $disponible = 35;
                } elseif ($anios >= 10) {
                    $disponible = 30;
                } elseif ($anios >= 5) {
                    $disponible = 25;
                } else {
                    $disponible = 20;
                }

                //control si existe la antiguedad
                $antiguedadModificar = Antiguedad::where('idagente', '=', $agenteModificar['idagente'])
                    ->where('año', '=', $row[2])
                    ->first();
                Log::info("Vericacion de antiguedad");
                Log::info($antiguedadModificar);

                if (empty($antiguedadModificar)) {
                    Log::info("Se insertara la antiguedad");
                    Antiguedad::create([
                        'idagente' => $agenteModificar['idagente'],
                        'año' => $row[2],
                        'pedido' => 0,
                        'vigente' => true,
                        'disponible' => $disponible,
                        'idusuario' => 1812
                    ]);
                }
            }

        }
    }


    /**
     * @inheritDoc
     */
    public function onFailure(Failure ...$failures)
    {

    }
}
