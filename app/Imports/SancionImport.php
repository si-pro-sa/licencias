<?php

namespace App\Imports;

use App\Models\Agente;
use App\Models\Antiguedad;
use App\Models\Sancion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;


class SancionImport implements ToCollection, SkipsOnFailure
{

    use SkipsFailures;

    public function collection(Collection $rows)
    {
        Log::info('entro al colection');
        foreach ($rows as $row) {
            $agenteModificar = Agente::where('documento', '=', $row[0])->first();
            if (!empty($agenteModificar)) {
                $sancionEncontrada = Sancion::where('expediente', '=', $row[1])->first();
                Log::info("Se busca sancion con");
                Log::info($row[1]);
                Log::info("----------------------");
                Log::info($sancionEncontrada);
                Log::info("Se insertara la sancion");
                    Sancion::create([
                        'idagente' => $agenteModificar['idagente'],
                        'expediente' => $row[1],
                        'resolucion' => $row[2],
                        'reseña' => $row[3],
                        'conclusion' => $row[4],                        
                        'acuerdo' => $row[5],
                        'idusuario' => 1812
                    ]);
                
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
