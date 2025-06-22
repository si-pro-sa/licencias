<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PuestoNoDebeSerPermanenteOTransitorio;
use App\Rules\AgenteDebeTenerPsicotecnicoAprobado;
use App\Rules\ReclamoNoDebeSuperponerEPDeCargo;
use App\Rules\AgenteNoDebeTenerMasDeUnReemplazo;
use App\Rules\AgenteDebeTenerPuesto;
use App\Models\Periodo;

class CreateReclamoDePagoCargoRequest extends FormRequest
{
    protected $idperiodo_ep;

    public function __construct($idperiodo_ep)
    {
        $this->idperiodo_ep = $idperiodo_ep;

    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'idagente' => [
                'required',
                'integer',
                new AgenteDebeTenerPuesto(
                    Periodo::find($this->idperiodo_ep)
                ),
                new PuestoNoDebeSerPermanenteOTransitorio(
                    Periodo::find($this->idperiodo_ep)
                ),
                new ReclamoNoDebeSuperponerEPDeCargo(
                    Periodo::find($this->idperiodo_ep)
                ),
                new AgenteNoDebeTenerMasDeUnReemplazo(
                    Periodo::find($this->idperiodo_ep)
                ),
                new AgenteDebeTenerPsicotecnicoAprobado(),
            ],

            'fdesde_ep' => 'required|date',
            'fhasta_ep' => 'required|date',

        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Debe seleccionar un :attribute',
            'fdesde_ep.required' => 'Debe ingresar una fecha desde de la EP',
            'fhasta_ep.required' => 'Debe ingresar una fecha hasta de la EP',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'idagente' => 'agente',
            'idefector' => 'efector',
            'idperiodo_ep' => 'periodo de efectiva prestacion',
            'fdesde_ep' => 'fecha desde de ep',
            'fhasta_ep' => 'fecha hasta de ep',
            'observaciones' => 'observaciones',
            'tipo_formulario_reclamo' => 'tipo de formulario de reclamo',
        ];
    }
}
