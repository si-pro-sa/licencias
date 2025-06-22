<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Http\Requests\CreateReclamoDePagoAPIRequest;
use App\Http\Resources\FormularioReclamoUpdateResource;
use App\Models\ReclamoDePago;
use App\Models\Periodo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Rules\PuestoNoDebeSerPermanenteOTransitorio;
use App\Rules\AgenteDebeTenerPsicotecnicoAprobado;
use App\Rules\ReclamoNoDebeSuperponerEPDeCargo;
use App\Rules\AgenteNoDebeTenerMasDeUnReemplazo;
use App\Factories\ReclamoDePagoFactory;
use App\Factories\FormRequestReclamoDePagoFactory;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\VisadoReclamoDePagoRequest;


class ReclamoDePagoAPIController extends AppBaseController
{
    public function index(Request $request, $idefector = null)
    {
        $data = FormularioReclamoUpdateResource::collection(
            ReclamoDePago::orderBy('created_at', 'DESC')
                ->efector($idefector)
                ->servicio($request->idservicio)
                ->periodoEP($request->idperiodo)
                ->agente($request->apellido_nombre)
                //->rangoCreacion($request->fdesde, $request->fhasta)
                 ->fDesdeCreado($request->fdesde)
                 ->fHastaCreado($request->fhasta)
                ->get()
        );
        if ($data) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Formularios cargados',
                    'formularios' => $data,
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'ocurrió un error al traer los formularios',
                ],
                400
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $formRequest = FormRequestReclamoDePagoFactory::validate($request, 'edit');

            $reclamo_de_pago = ReclamoDePago::create([
                'expediente' => $request->expediente,
                'idtipo_formulario_reclamo_de_pago' => $request->idtipo_formulario_reclamo_de_pago,
                'idagente' => $request->idagente,
                'idefector' => $request->idefector,
                'idservicio' => $request->idservicio,
                'idperiodo_ep' => $request->idperiodo_ep,
                'fdesde_ep' => $request->fdesde_ep,
                'fhasta_ep' => $request->fhasta_ep,
                'observacion_efector' => $request->observacion_efector,
            ]);

            if ($reclamo_de_pago) {
                return $this->sendResponse(
                    $reclamo_de_pago,
                    'Reclamo de pago creado',
                    201
                );
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()],422);
        }
    }

    public function get($id)
    {
        $reclamo_de_pago = FormularioReclamoUpdateResource::make(
            ReclamoDePago::findOrFail($id)
        );

        return response()->json(['data' => $reclamo_de_pago], 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $formRequest = FormRequestReclamoDePagoFactory::validate($request, 'edit');

            $reclamo_de_pago = ReclamoDePago::findOrFail($id);

            $reclamo_de_pago->update([
                'expediente' => $request->expediente,
                'idtipo_formulario_reclamo_de_pago' => $request->idtipo_formulario_reclamo_de_pago,
                'idagente' => $request->idagente,
                'idefector' => $request->idefector,
                'idservicio' => $request->idservicio,
                'idperiodo_ep' => $request->idperiodo_ep,
                'fdesde_ep' => $request->fdesde_ep,
                'fhasta_ep' => $request->fhasta_ep,
                'observacion_efector' => $request->observacion_efector,
            ]);

            return $this->sendResponse(
                $reclamo_de_pago,
                'Reclamo de pago actualizado',
                200
            );
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()],422);
        }
    }

    public function agregarObservacionAdicional(Request $request, $id)
    {
        $this->validate($request, [
            'observacion_adicional' => 'required',
        ]);
        $reclamo_de_pago = ReclamoDePago::find($id);
        $reclamo_de_pago->observacion_adicional = $request->observacion_adicional;
        $reclamo_de_pago->save();

        return $this->sendResponse(
            $reclamo_de_pago,
            'Observación agregada',
            200
        );

    }

    public function visar(Request $request, $id, $idtipo_visado)
    {
        $reclamo_de_pago = ReclamoDePago::findOrFail($id);
        $reclamo_de_pago->idtipo_visado = $idtipo_visado;
        if ($idtipo_visado == 2) {
            try {
                FormRequestReclamoDePagoFactory::validate($request, 'visado');

                $reclamo_de_pago->idperiodo_liquidacion = Periodo::getPeriodoConFecha(null)->idperiodo+1;

                $reclamo_de_pago->visado_by = Auth::user()->idusuario;
            } catch (ValidationException $e) {
                return response()->json(['errors' => $e->errors()], 422);
            }
        }
        $reclamo_de_pago->fecha_visado = now();
        $reclamo_de_pago->save();

        return $this->sendResponse(
            $reclamo_de_pago,
            'Reclamo de pago visado correctamente',
            200
        );
    }

    public function destroy($id)
    {
        $reclamo_de_pago = ReclamoDePago::findOrFail($id);

        if ($reclamo_de_pago->idtipo_visado === 1) {
            $reclamo_de_pago->delete();
            return $this->sendResponse(
                $reclamo_de_pago,
                'Reclamo de pago eliminado',
                200
            );
        }
    }
}
