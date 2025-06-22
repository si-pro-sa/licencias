<?php
namespace App\Exports;

use App\Models\ReemplazoCupoPuesto;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ReemplazoCupoExport implements FromArray, WithHeadings
{
    use Exportable;
    private $reemplazosCupo;

    public function array() : array
    {
        $data = [];
        // dd($this->reemplazosCupo);
        foreach ($this->reemplazosCupo as $key => $reemplazo) {
            $reemplazoPuesto = ReemplazoCupoPuesto::with([
            'reemplazocupo',
            'puestos',
            'servicio',
        ])->find($reemplazo['idreemplazo_cupo_puesto']);

        $fechaEfectorActualizado = $reemplazo['reemplazocupo']['estado'] && 
                                  strtotime($reemplazo['reemplazocupo']['updated_at']) !==  
                                  strtotime($reemplazo['reemplazocupo']['created_at']) ? 
                                  date('Y-m-d H:i', strtotime($reemplazo['reemplazocupo']['updated_at']))
                                  : '';

        $cupoEfectorActualizado = strtotime($reemplazo['reemplazocupo']['updated_at']) !==  
                               strtotime($reemplazo['reemplazocupo']['created_at']) ? 
                               $reemplazo['reemplazocupo']['cupo_max'] 
                               : '';

        $fechaPuestoActualizado = $reemplazoPuesto->estado && 
                                  strtotime($reemplazoPuesto->updated_at) !==  
                                  strtotime($reemplazoPuesto->created_at) ? 
                                  date('Y-m-d H:i', strtotime($reemplazoPuesto->updated_at))
                                  : '';

        $cupoPuestoCreacion = strtotime($reemplazoPuesto->updated_at) !==  
                              strtotime($reemplazoPuesto->created_at) ? 
                              $reemplazoPuesto->cupo
                              : '';

        $usuarioActualizado = $reemplazo['reemplazocupo']['estado'] && 
                                  strtotime($reemplazo['reemplazocupo']['updated_at']) !==  
                                  strtotime($reemplazo['reemplazocupo']['created_at']) ? 
                                  $reemplazo['reemplazocupo']['usuario']
                                  : '';
        $usuarioPuestoActualizado = $reemplazoPuesto->estado && 
                                  strtotime($reemplazoPuesto->updated_at) !==  
                                  strtotime($reemplazoPuesto->created_at) ? 
                                  $reemplazoPuesto->updatedBy->nombreusuario
                                  : '';

            $data[$key] = [
                // $reemplazo['idreemplazo_cupo_puesto'],
                $reemplazo['reemplazocupo']['periodo']['periodo'],
                $reemplazo['reemplazocupo']['dependencia']['iddependencia'],
                $reemplazo['reemplazocupo']['dependencia']['dependencia'],
                $reemplazo['totalagentesefector'],
                $reemplazoPuesto ? $reemplazoPuesto->getCupoEfectorCreacion() : '',
                // $reemplazo['reemplazocupo']['usuario'],
                $reemplazo['reemplazocupo']['created_by']['nombreusuario'],
                date('Y-m-d H:i', strtotime($reemplazo['reemplazocupo']['created_at'])),
                $usuarioActualizado,
                $fechaEfectorActualizado,
                $cupoEfectorActualizado,
                $reemplazo['servicio']['iddependencia'],
                $reemplazo['servicio']['dependencia'],
                $reemplazo['totalagentesservicio'],
                $reemplazo['puesto'],
                $reemplazoPuesto ? $reemplazoPuesto->getCupoPuestoCreacion() : '',
                $reemplazo['eventual'] === '-' ? '-' : 'EVENTUAL',
                $reemplazo['created_by']['nombreusuario'],
                date('Y-m-d H:i', strtotime($reemplazo['created_at'])),
                // $reemplazo['updated_by']['nombreusuario'],
                $usuarioPuestoActualizado,
                $fechaPuestoActualizado,
                $cupoPuestoCreacion,
                $reemplazo['reemplazocupo']['observaciones'],
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'PERIODO',
            'ID EFECTOR',
            'EFECTOR',
            'TOTAL AGENTES EFECTOR',
            'CUPO EFECTOR',
            'USUARIO CREACION',
            'FECHA CREACION',
            'USUARIO ACTUALIZACION',
            'FECHA ACTUALIZACION',
            'CUPO EFECTOR ACTUALIZADO',
            'ID SERVICIO',
            'SERVICIO',
            'TOTAL AGENTES SERVICIO',
            'PUESTO',
            'CUPO PUESTO',
            'EVENTUAL',
            'USUARIO CREACION',
            'FECHA CREACION',
            'USUARIO ACTUALIZACION',
            'FECHA ACTUALIZACION',
            'CUPO PUESTO ACTUALIZADO',
            'OBSERVACIONES',
        ];
    }

    public function __construct($reemplazosCupo)
    {
        $this->reemplazosCupo = $reemplazosCupo->toArray()['data'];
    }
}
