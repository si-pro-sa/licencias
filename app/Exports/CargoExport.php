<?php
namespace App\Exports;

use App\Models\CargoCambioEstado;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class CargoExport implements FromArray, WithHeadings
{
    use Exportable;
    private $cargos;

    public function array() : array
    {
        $data = [];
        foreach ($this->cargos as $key => $cargoCambioEstado) {
            $cambioEstado = CargoCambioEstado::with([
                'cargo',
                'cargo.efector',
                'cargo.servicio',
                'cargo.tipoCese',
                'cargo.cargoReemplazado',
                'cargo.cargoReemplazado.tipoNivel',
                'cargo.cargoReemplazado.tipoFuncion',
                'cargo.tipoCampania',
                'cargo.tipoEspecialidad'
            ])
                ->find($cargoCambioEstado['idcargo_cambio_estado']);

            $data[$key] = [
                $cargoCambioEstado['cargo']['tipo_campania']['tipocampania'],
                $cargoCambioEstado['idcargo'],
                $cargoCambioEstado['idcargo_cambio_estado'],
                $cargoCambioEstado['cargo_tipo_formulario_excel'],
                $cargoCambioEstado['cargo']['tipo_cargo']['tipocargo'],
                $cargoCambioEstado['cargo']['tipo_agrupamiento']['tipoagrupamiento'],
                $cargoCambioEstado['cargo_tipo_formulario_excel'] !== 'BAJA' ? $cargoCambioEstado['cargo']['tipo_cese']['tipocese'] : $cargoCambioEstado['cargo_tipo_formulario']['cargotipo_formulario'],
                $cargoCambioEstado['cargo']['agente_propuesto']['apellido_nombre'],
                $cargoCambioEstado['cargo']['agente_propuesto']['documento'],
                $cargoCambioEstado['cargo']['tipo_especialidad']['tipoespecialidad'],
                $cargoCambioEstado['cargo']['tipo_funcion']['tipofuncion'],
                $cargoCambioEstado['cargo']['tipo_nivel']['tiponivel'],
                $cargoCambioEstado['cargo']['cargo_reemplazado']['apellido_nombre'] ?? '',
                $cargoCambioEstado['cargo']['cargo_reemplazado']['documento'] ?? '',
                $cargoCambioEstado['cargo']['cargo_reemplazado']['funcion'] ?? '',
                $cargoCambioEstado['cargo']['cargo_reemplazado']['nivel'] ?? '',
                $cambioEstado->cargo->diagramacionCoberturaToString(),
//                $cargoCambioEstado['cargo']['servicio']['dependencia'],
                $cargoCambioEstado['cargo']['servicio_calculado'],
                $cargoCambioEstado['fecha_desde_formateada'],
                $cargoCambioEstado['fecha_hasta_formateada'],
                $cargoCambioEstado['fecha_designacion_transitorio_formateada'],
                $cargoCambioEstado['cargo_tipo_visado']['cargotipo_visado'],
                $cargoCambioEstado['cargo']['efector']['dependencia'],
                $cargoCambioEstado['cargo_tipo_formulario_excel'] !== 'BAJA' ? $cargoCambioEstado['cargo']['razones_brecha'] : $cargoCambioEstado['motivo'],
                $cargoCambioEstado['cargo']['produccion_esperada'],
                $cargoCambioEstado['cargo']['prioritario_formateado'],
                $cargoCambioEstado['cargo']['agente_propuesto']['edad'],
                $cargoCambioEstado['cargo']['agente_propuesto']['antiguedad_formateada'],
                $cambioEstado->cargo->dotacionToString(),
                $cambioEstado->cargo->dotacionToString(false),
                $cargoCambioEstado['cargo_tipo_firma']['cargotipo_firma'] ?? '',
                $cargoCambioEstado['cargo']['tipo_referido_formateado'],
                $cargoCambioEstado['fecha_creado_formateada'],
                $cargoCambioEstado['observaciones_internas'],
                $cargoCambioEstado['cargo']['resumen_evaluacion'],
                $cargoCambioEstado['cargo']['agente_propuesto']['telefono'],
                $cargoCambioEstado['cargo']['curso_induccion'],
                $cargoCambioEstado['fecha_recibido_formateada'],
                $cargoCambioEstado['fecha_envio_formateada'],
                $cargoCambioEstado['fecha_retorno_formateada'],
                $cargoCambioEstado['fecha_entrega_organismo_formateada'],
                $cambioEstado->cargo->getCantidadHorasEfectores(),
                'NO'
            ];
        }
        return $data;
    }

    public function headings(): array
    {
        return [
            'CAMPAÑA',
            'ID CARGO',
            'ID FORMULARIO',
            'TIPO DE FORMULARIO',
            'TIPO DE CARGO',
            'AGRUPAMIENTO',
            'CAUSAL',
            'REEMPLAZANTE',
            'DNI',
            'ESPECIALIDAD',
            'FUNCION',
            'NIVEL',
            'TITULAR',
            'DNI',
            'FUNCION',
            'NIVEL',
            'HORARIOS',
            'SERVICIO',
            'FECHA DESDE',
            'FECHA HASTA',
            'FECHA DESIGNACION TRANSITORIO',
            'VISADO',
            'EFECTOR',
            'RAZONES DE BRECHA',
            'TAREAS Y PRODUCCIÓN',
            'PRIORITARIO',
            'EDAD',
            'ANTIGUEDAD',
            'DOTACION DE SERVICIO',
            'DOTACION DE EFECTOR',
            'FIRMA',
            'REFERIDO',
            'FECHA CREACION',
            'OBSERVACIONES INTERNAS',
            'EVALUACION',
            'TELEFONO',
            'INDUCCION',
            'FECHA RECIBIDO',
            'FECHA ENVIO',
            'FECHA RETORNO',
            'FECHA ENTREGA ORGANISMO',
            'MODALIDAD AO 18/12',
            'LIQUIDACION DEVENGADA'
        ];
    }

    public function __construct($cargos)
    {
        $this->cargos = $cargos->toArray()['data'];
    }
}
