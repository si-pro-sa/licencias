<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\PuestoNoDebeSerPermanenteOTransitorio;
use App\Rules\AgenteDebeTenerPsicotecnicoAprobado;
use App\Rules\ReclamoNoDebeSuperponerEPDeCargo;
use App\Rules\AgenteNoDebeTenerMasDeUnReemplazo;
use App\Models\Periodo;

class VisadoReclamoDePagoCargoRequest extends FormRequest
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
                new PuestoNoDebeSerPermanenteOTransitorio(
                    Periodo::find($this->idperiodo_ep)
                ),
                new ReclamoNoDebeSuperponerEPDeCargo(
                    Periodo::find($this->idperiodo_ep)
                ),
                new AgenteNoDebeTenerMasDeUnReemplazo(
                    Periodo::find($this->idperiodo_ep)
                ),
            ],
            'idperiodo_ep' => ['required','integer'],
        ];
    }
}
