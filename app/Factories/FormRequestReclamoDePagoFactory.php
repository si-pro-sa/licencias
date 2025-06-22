<?php

namespace App\Factories;

use App\Http\Requests\CreateReclamoDePagoCargoRequest;
use App\Http\Requests\CreateReclamoDePagoLDRequest;
use App\Http\Requests\CreateReclamoDePagoReemplazoRequest;
use App\Http\Requests\CreateReclamoDePagoGuardiaRequest;
use App\Http\Requests\VisadoReclamoDePagoCargoRequest;
use App\Http\Requests\VisadoReclamoDePagoReemplazoRequest;
use App\Http\Requests\VisadoReclamoDePagoLDRequest;
use App\Http\Requests\VisadoReclamoDePagoGuardiaRequest;


use App\Models\ReclamoDePago;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
class FormRequestReclamoDePagoFactory
{
    public static function validate(Request $request, $tipo_validacion)
    {
        switch ($tipo_validacion) {
            case 'create':
            case 'edit':
                return self::validateCreateOrUpdate($request);
            case 'visado':
                return self::validateVisado($request);
            default:
                throw new \InvalidArgumentException('Tipo de validación no encontrada');
        }
    }


        public static function validateCreateOrUpdate(Request $request)
        {
            $request->validate(ReclamoDePago::$rules);
            $tipo_formulario = $request->input('idtipo_formulario_reclamo_de_pago');

            switch ($tipo_formulario) {
                case 1:
                        $formRequest = new CreateReclamoDePagoCargoRequest($request->idperiodo_ep);
                        break;
                case 2:
                        $formRequest = new CreateReclamoDePagoReemplazoRequest();
                        break;
                case 3:
                        $formRequest = new CreateReclamoDePagoLDRequest();
                        break;
                case 4:
                        $formRequest = new CreateReclamoDePagoGuardiaRequest();
                        break;
                default:
                        throw new \InvalidArgumentException('Tipo de formulario no válido');
            }
            $request->validate($formRequest->rules());
            return $formRequest;
        }

        public static function validateVisado(Request $request)
        {
            $tipo_formulario = $request->input('idtipo_formulario_reclamo_de_pago');

            switch ($tipo_formulario) {
                case 1:
                        $formRequest = new VisadoReclamoDePagoCargoRequest($request->idperiodo_ep);
                        break;
                case 2:
                        $formRequest = new VisadoReclamoDePagoReemplazoRequest();
                        break;
                case 3:
                        $formRequest = new VisadoReclamoDePagoLDRequest();
                        break;
                case 4:
                        $formRequest = new VisadoReclamoDePagoGuardiaRequest();
                        break;
                default:
                        throw new \InvalidArgumentException('Tipo de formulario no válido');
            }
            $request->validate($formRequest->rules());
            return $formRequest;
        }
}
